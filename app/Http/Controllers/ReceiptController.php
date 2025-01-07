<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Receipt;
use App\Models\ReceiptDetail;
use App\Models\Accident;
use App\Models\Location;
use App\Models\ResponsibilityFine;
use App\Models\FineType;
use App\Models\AccidentType;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendPushNotification;
use Kutia\Larafirebase\Facades\Larafirebase;
use ExpoSDK\Expo;
use App\Models\ExpoToken;
use ExpoSDK\ExpoMessage;
use Image;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('receipts.view', Receipt::class);
        return view('receipts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        foreach ($request->type_id as $index => $typeId) {
            $type = $request->type[$index];
            $payment = (float) $request->payment[$index];
            switch ($type) {
                case 'Fine':
                    $table = 'fines';
                    $column = 'amount';
                    break;

                case 'Accident':
                    $table = 'accidents';
                    $column = 'responsibility_amount';
                    break;

                case 'Deduction':
                    $table = 'deductions';
                    $column = 'amount';
                    break;

                default:
                    "null";
            }
            $currentValue = DB::table($table)
                ->where('id', $typeId)
                ->value($column);
            if ($currentValue !== null) {
                $newValue = max(0, $currentValue - $payment);
                DB::table($table)
                    ->where('id', $typeId)
                    ->update([
                        $column => $newValue,
                        'previous_total' => $currentValue,
                    ]);
            }
        }

        $receipt = Receipt::create([
            'receipt_id' => $request->receipt_id,
            'total_amount' => $request->total_payable,
            'slip_id' => $request->slip_id,
            'user_id' => $request->user_id,
            'date' => Carbon::createFromFormat('d-m-Y', $request->receipt_date)->format('Y-m-d'), // Convert to Y-m-d format
            'deduction_way' => $request->deduction_way,
            'added_by' => Auth::user()->id,
        ]);


        foreach ($request->type_id as $index => $typeId) {
            ReceiptDetail::create([
                'receipt_id' => $request->receipt_id,
                'type_id' => $typeId,
                'type' => $request->type[$index],
                'payment' => $request->payment[$index],
                'description' => $request->description[$index] ?? '',
            ]);
        }
        return redirect()->route('receipts')->with('success', 'Receipt created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $parts = explode('-', $id);
        $receipt_id = $parts[1];
        $group_id = [2, 3];
        $users = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->whereIn('users_groups.group_id', $group_id)
            ->select('users.id', 'users.username')
            ->get();
        $receipt = DB::table('receipt')
            ->leftJoin('receipt_details', 'receipt_details.receipt_id', '=', 'receipt.receipt_id')
            ->where('receipt.receipt_id', $receipt_id)
            ->select('receipt.*', 'receipt_details.*')
            ->get();
        // Iterate through each receipt item and calculate the previous amount
        foreach ($receipt as $item) {
            $previousAmount = null;

            switch ($item->type) {
                case 'Fine':
                    // $previousAmount = DB::table('fines')
                    //     ->where('id', $item->type_id)
                    //     ->value('amount');
                    $previousAmount = DB::table('fines')
                        ->join('assets', 'fines.asset_id', '=', 'assets.id')
                        ->where('fines.id', $item->type_id) // Specify the table for `id`
                        ->select(
                            'fines.amount as amount',
                            'fines.id as id',
                            'assets.asset_tag as tag'
                        )
                        ->first();
                    break;

                    // case 'Accident':
                    //     $previousAmount = DB::table('accidents')
                    //         ->where('id', $item->type_id)
                    //         ->value('responsibility_amount');
                    //     break;
                case 'Accident':
                    $previousAmount = DB::table('accidents')
                        ->join('assets', 'accidents.asset_id', '=', 'assets.id')
                        ->where('accidents.id', $item->type_id) // Specify the table for `id`
                        ->select(
                            'responsibility_amount',
                            'accidents.id as id',
                            'assets.asset_tag as tag'
                        )
                        ->first();
                    break;


                case 'Deduction':
                    // $previousAmount = DB::table('deductions')
                    //     ->where('id', $item->type_id)
                    //     ->value('amount');
                    $previousAmount = DB::table('deductions')
                        ->where('id', $item->type_id)
                        ->select(
                            'deductions.amount as amount',
                            'accidents.id as id',
                            'assets.asset_tag as tag'
                        )
                        ->first();
                    break;
            }
            $item->previous_amount = $previousAmount->responsibility_amount ?? $previousAmount->amount;
            $item->asset_tag = $previousAmount->tag ?? $previousAmount->id;
        }
        // dd($receipt);
        $totalPayment = $receipt->sum('payment');
        return view('receipts/edit', compact('users', 'receipt', 'totalPayment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Retrieve all request data
        $data = $request->all();

        // Format the receipt date
        $data['receipt_date'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['receipt_date'])->format('Y-m-d');

        // Update the receipt details in the 'receipt' table
        DB::table('receipt')
            ->where('receipt_id', $data['receipt_id'])
            ->update([
                'user_id' => $data['user_id'],
                'date' => $data['receipt_date'],
                'deduction_way' => $data['deduction_way'],
                'slip_id' => $data['slip_id'],
            ]);

        // Iterate over the payment types and update corresponding tables
        foreach ($data['type'] as $key => $type) {
            $payment = $data['payment'][$key];
            $typeId = $data['type_id'][$key];

            switch ($type) {
                case 'Fine':
                    DB::table('fines')
                        ->where('id', $typeId)
                        ->decrement('amount', $payment);
                    break;

                case 'Accident':
                    DB::table('accidents')
                        ->where('id', $typeId)
                        ->decrement('responsibility_amount', $payment);
                    break;

                case 'Deduction':
                    DB::table('deductions')
                        ->where('id', $typeId)
                        ->decrement('amount', $payment);
                    break;

                default:
                    break;
            }
        }

        // Retrieve existing receipt details to calculate cumulative payment
        $existingDetails = ReceiptDetail::where('receipt_id', $data['receipt_id'])->get();

        // Initialize receiptDetails for insertion
        $receiptDetails = [];
        foreach ($data['type'] as $key => $type) {
            $typeId = $data['type_id'][$key];
            $payment = $data['payment'][$key];
            $description = trim($data['description'][$key]);

            // Calculate cumulative payment
            $previousPayment = $existingDetails->where('type_id', $typeId)->sum('payment');
            $cumulativePayment = $previousPayment + $payment;

            $receiptDetails[] = [
                'receipt_id' => $data['receipt_id'],
                'type_id' => $typeId,
                'type' => $type,
                'payment' => $cumulativePayment,
                'description' => $description,
            ];
        }

        // Delete existing details and insert the updated ones
        ReceiptDetail::where('receipt_id', $data['receipt_id'])->delete();
        ReceiptDetail::insert($receiptDetails);

        // Redirect with success message
        return redirect()->route('receipts')->with('success', 'Receipt updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $parts = explode('-', $id);
        $receipt_id = $parts[1];
        $this->authorize('delete', Receipt::class);
        $receipt = Receipt::where('receipt_id', $receipt_id)->first();

        //dd($receipt);
        if (!$receipt) {
            return redirect()->route('receipts')->with('error', 'Receipt not found.');
        }

        $receipt->update(['deleted_at' => now()]);
        return redirect()->route('receipts')->with('success', 'Receipt  deleted successfully.');
    }

    public function getReceiptsdetail(Request $request)
    {
        $userId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate) {
            try {
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Invalid start date format. Please use dd-mm-yyyy.'], 400);
                }
            }
        }

        // Validate and parse the end date
        if ($endDate) {
            try {
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Invalid end date format. Please use dd-mm-yyyy.'], 400);
                }
            }
        }


        $receiptsQuery = DB::table('receipt')
            ->select('receipt.receipt_id', 'receipt.date', DB::raw('SUM(receipt_details.payment) as total_payment'))
            ->join('receipt_details', 'receipt.receipt_id', '=', 'receipt_details.receipt_id')
            ->groupBy('receipt.receipt_id', 'receipt.date');

        if ($userId) {
            $receiptsQuery->where('receipt.user_id', $userId);
        }
        if ($startDate && $endDate) {
            $receiptsQuery->whereBetween('receipt.date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $receiptsQuery->where('receipt.date', '>=', $startDate);
        } elseif ($endDate) {
            $receiptsQuery->where('receipt.date', '<=', $endDate);
        }

        $receipts = $receiptsQuery->get()->map(function ($receipt) {
            return (object) [
                'type' => 'receipt',
                'id' => $receipt->receipt_id,
                'date' => $receipt->date,
                'total_payment' => $receipt->total_payment,
            ];
        });

        $fetchInvoices = function ($table, $defaultAmountColumn, $dateColumn, $fallbackColumn = null, $typeTable = null) use ($userId, $startDate, $endDate) {
            $query = DB::table($table)
                ->select(
                    'id',
                    DB::raw("IFNULL($defaultAmountColumn, $fallbackColumn) as total_payment"),
                    DB::raw("$dateColumn as date")
                )
                ->where('id', '>', 0);

            if ($userId) {
                $query->where('user_id', $userId);
            }
            if ($startDate && $endDate) {
                $query->whereBetween(DB::raw("DATE($dateColumn)"), [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where(DB::raw("DATE($dateColumn)"), '>=', $startDate);
            } elseif ($endDate) {
                $query->where(DB::raw("DATE($dateColumn)"), '<=', $endDate);
            }

            return $query->get()->map(function ($item) use ($typeTable) {
                return (object) [
                    'type' => 'invoice',
                    'type_table' => $typeTable,
                    'id' => $item->id,
                    'date' => $item->date,
                    'total_payment' => $item->total_payment,
                ];
            });
        };

        // Define table-specific columns and type_table values
        $accidents = $fetchInvoices('accidents', 'previous_total', 'accident_date', 'responsibility_amount', 'accident');
        $fines = $fetchInvoices('fines', 'previous_total', 'fine_date', 'amount', 'fine');
        $deductions = $fetchInvoices('deductions', 'previous_total', 'deduction_date', 'amount', 'deduction');

        $invoices = $accidents->merge($fines)->merge($deductions);
        $results = $receipts->merge($invoices);

        return response()->json($results);
    }


    // public function getReceiptsdetail(Request $request)
    // {
    //     $userId = $request->input('user_id');
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     if ($startDate) {
    //         try {
    //             $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
    //         } catch (\Exception $e) {
    //             return response()->json(['error' => 'Invalid start date format. Please use dd-mm-yyyy.'], 400);
    //         }
    //     }
    //     if ($endDate) {
    //         try {
    //             $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
    //         } catch (\Exception $e) {
    //             return response()->json(['error' => 'Invalid end date format. Please use dd-mm-yyyy.'], 400);
    //         }
    //     }
    //     $receiptsQuery = DB::table('receipt')
    //         ->select('receipt.receipt_id', 'receipt.date', DB::raw('SUM(receipt_details.payment) as total_payment'))
    //         ->join('receipt_details', 'receipt.receipt_id', '=', 'receipt_details.receipt_id')
    //         ->groupBy('receipt.receipt_id', 'receipt.date');

    //     if ($userId) {
    //         $receiptsQuery->where('receipt.user_id', $userId);
    //     }
    //     if ($startDate && $endDate) {
    //         $receiptsQuery->whereBetween('receipt.date', [$startDate, $endDate]);
    //     } elseif ($startDate) {
    //         $receiptsQuery->where('receipt.date', '>=', $startDate);
    //     } elseif ($endDate) {
    //         $receiptsQuery->where('receipt.date', '<=', $endDate);
    //     }

    //     $receipts = $receiptsQuery->get()->map(function ($receipt) {
    //         return (object) [
    //             'type' => 'receipt',
    //             'id' => $receipt->receipt_id,
    //             'date' => $receipt->date,
    //             'total_payment' => $receipt->total_payment,
    //         ];
    //     });

    //     $fetchInvoices = function ($table, $defaultAmountColumn, $dateColumn, $fallbackColumn = null) use ($userId, $startDate, $endDate) {
    //         $query = DB::table($table)
    //             ->select('id', 
    //                 DB::raw("IFNULL($defaultAmountColumn, $fallbackColumn) as total_payment"), 
    //                 DB::raw("$dateColumn as date"))
    //             ->where('id', '>', 0);

    //         if ($userId) {
    //             $query->where('user_id', $userId);
    //         }
    //         if ($startDate && $endDate) {
    //             $query->whereBetween(DB::raw("DATE($dateColumn)"), [$startDate, $endDate]);
    //         } elseif ($startDate) {
    //             $query->where(DB::raw("DATE($dateColumn)"), '>=', $startDate);
    //         } elseif ($endDate) {
    //             $query->where(DB::raw("DATE($dateColumn)"), '<=', $endDate);
    //         }

    //         return $query->get()->map(function ($item) {
    //             return (object) [
    //                 'type' => 'invoice',
    //                 'id' => $item->id,
    //                 'date' => $item->date,
    //                 'total_payment' => $item->total_payment,
    //             ];
    //         });
    //     };

    //     // Define table-specific columns for fallback
    //     $accidents = $fetchInvoices('accidents', 'previous_total', 'accident_date', 'responsibility_amount');
    //     $fines = $fetchInvoices('fines', 'previous_total', 'fine_date', 'amount');
    //     $deductions = $fetchInvoices('deductions', 'previous_total', 'deduction_date', 'amount');

    //     $invoices = $accidents->merge($fines)->merge($deductions);
    //     $results = $receipts->merge($invoices);

    //     return response()->json($results);
    // }


    public function CreateReceipt()
    {
        $group_id = [2, 3];
        $users = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->whereIn('users_groups.group_id', $group_id)
            ->select('users.id', 'users.username')
            ->get();

        // Calculate the next receipt ID
        $nextReceiptId = Receipt::max('receipt_id') + 1;

        return view('receipts/create', compact('users', 'nextReceiptId'));
    }
    public function GetReceipt($id)
    {
        $receipt = DB::table('receipt')
            ->leftJoin('receipt_details', 'receipt_details.receipt_id', '=', 'receipt.receipt_id')
            ->leftJoin('users', 'users.id', '=', 'receipt.user_id') // Add this join to get the user name
            ->where('receipt.receipt_id', $id)
            ->select('receipt.*', 'receipt_details.*', 'users.username as user_name') // Select the user name as 'user_name'
            ->get();

        // Iterate through each receipt item and calculate the previous amount
        // foreach ($receipt as $item) {
        //     $previousAmount = null;

        //     switch ($item->type) {
        //         case 'Fine':
        //             $previousAmount = DB::table('fines')
        //                 ->where('id', $item->type_id)
        //                 ->value('amount');
        //             break;

        //         case 'Accident':
        //             $previousAmount = DB::table('accidents')
        //                 ->where('id', $item->type_id)
        //                 ->value('responsibility_amount');
        //             break;

        //         case 'Deduction':
        //             $previousAmount = DB::table('deductions')
        //                 ->where('id', $item->type_id)
        //                 ->value('amount');
        //             break;
        //     }

        //     // Assign the calculated previous_amount to the current receipt item
        //     $item->previous_amount = $previousAmount;
        // }
        foreach ($receipt as $item) {
            $previousAmount = null;

            switch ($item->type) {
                case 'Fine':
                    // $previousAmount = DB::table('fines')
                    //     ->where('id', $item->type_id)
                    //     ->value('amount');
                    $previousAmount = DB::table('fines')
                        ->join('assets', 'fines.asset_id', '=', 'assets.id')
                        ->where('fines.id', $item->type_id) // Specify the table for `id`
                        ->select(
                            'fines.amount as amount',
                            'fines.id as id',
                            'assets.asset_tag as tag'
                        )
                        ->first();
                    break;

                    // case 'Accident':
                    //     $previousAmount = DB::table('accidents')
                    //         ->where('id', $item->type_id)
                    //         ->value('responsibility_amount');
                    //     break;
                case 'Accident':
                    $previousAmount = DB::table('accidents')
                        ->join('assets', 'accidents.asset_id', '=', 'assets.id')
                        ->where('accidents.id', $item->type_id) // Specify the table for `id`
                        ->select(
                            'responsibility_amount',
                            'accidents.id as id',
                            'assets.asset_tag as tag'
                        )
                        ->first();
                    break;


                case 'Deduction':
                    // $previousAmount = DB::table('deductions')
                    //     ->where('id', $item->type_id)
                    //     ->value('amount');
                    $previousAmount = DB::table('deductions')
                        ->where('id', $item->type_id)
                        ->select(
                            'deductions.amount as amount',
                            'accidents.id as id',
                            'assets.asset_tag as tag'
                        )
                        ->first();
                    break;
            }
            $item->previous_amount = $previousAmount->responsibility_amount ?? $previousAmount->amount;
            $item->asset_tag = $previousAmount->tag ?? $previousAmount->id;
        }

        // Now return the modified receipt data after the loop completes
        return response()->json($receipt);
    }


    public function downloadReceipt($id)
    {
        //dd($id);
        $receipt = DB::table('receipt')
            ->leftJoin('receipt_details', 'receipt_details.receipt_id', '=', 'receipt.receipt_id')
            ->leftJoin('users', 'users.id', '=', 'receipt.user_id')
            ->where('receipt.receipt_id', $id)
            ->select('receipt.*', 'receipt_details.*', 'users.username as user_name')
            ->get();

        foreach ($receipt as $item) {
            $previousAmount = null;

            switch ($item->type) {
                case 'Fine':
                    $previousAmount = DB::table('fines')
                        ->where('id', $item->type_id)
                        ->value('amount');
                    break;

                case 'Accident':
                    $previousAmount = DB::table('accidents')
                        ->where('id', $item->type_id)
                        ->value('responsibility_amount');
                    break;

                case 'Deduction':
                    $previousAmount = DB::table('deductions')
                        ->where('id', $item->type_id)
                        ->value('amount');
                    break;
            }

            $item->previous_amount = $previousAmount;
        }
        //dd($receipt);
        $pdf = Pdf::loadView('receipts.pdf', compact('receipt'));
        //dd($pdf);
        $pdfContent = $pdf->output();

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=Adjustment-{$id}.pdf");
    }

    public function showInvoice(Request $request, $id)
    {
        $invoiceId = $id;
        $typeTable = $request->input('type_table');  // Get type_table from the query string

        $responseData = [];

        if ($typeTable == 'fine') {
            $records = DB::table('fines')
                ->join('users', 'fines.user_id', '=', 'users.id')  // Join with users table on user_id
                ->where('fines.id', $invoiceId)
                ->select('fines.*', 'users.username')
                ->get();

            foreach ($records as $record) {
                $record->amount = $record->previous_total ? $record->previous_total : $record->amount;
                $record->date = $record->fine_date; // Rename fine_date to date
                unset($record->fine_date);         // Remove the original column
                $responseData[] = $record;
            }
        } elseif ($typeTable == 'deduction') {
            $records = DB::table('deductions')
                ->join('users', 'deductions.user_id', '=', 'users.id')  // Join with users table on user_id
                ->where('deductions.id', $invoiceId)
                ->select('deductions.*', 'users.username')
                ->get();

            foreach ($records as $record) {
                $record->amount = $record->previous_total ? $record->previous_total : $record->amount;
                $record->date = $record->deduction_date; // Rename deduction_date to date
                unset($record->deduction_date);         // Remove the original column
                $responseData[] = $record;
            }
        } elseif ($typeTable == 'accident') {
            $records = DB::table('accidents')
                ->join('users', 'accidents.user_id', '=', 'users.id')  // Join with users table on user_id
                ->where('accidents.id', $invoiceId)
                ->select('accidents.*', 'users.username')
                ->get();

            foreach ($records as $record) {
                $record->amount = $record->previous_total ? $record->previous_total : $record->responsibility_amount;
                $record->date = $record->accident_date; // Rename accident_date to date
                unset($record->accident_date);         // Remove the original column
                $responseData[] = $record;
            }
        }

        return response()->json($responseData);
    }
}
