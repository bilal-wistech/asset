<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
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


class AccidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SaveReceipt(Request $request)
    {
        // $existingReceipt = Receipt::where('receipt_id', $request->receipt_id)->first();

        // if ($existingReceipt) {
        //     // If the receipt_id already exists, redirect back with an error message
        //     return redirect()->back()->with('error', 'Receipt ID already exists. Please provide a unique ID.');
        // }
        //dd($request->all());

        // foreach ($request->type_id as $index => $typeId) {
        //     $type = $request->type[$index];
        //     $payment = (float) $request->payment[$index];
        //     switch ($type) {
        //         case 'Fine':
        //             $table = 'fines';
        //             $column = 'amount';
        //             break;

        //         case 'Accident':
        //             $table = 'accidents';
        //             $column = 'responsibility_amount';
        //             break;

        //         case 'Deduction':
        //             $table = 'deductions';
        //             $column = 'amount';
        //             break;

        //         default:
        //             "null"; 
        //     }
        //     $currentValue = DB::table($table)
        //         ->where('id', $typeId)
        //         ->value($column);
        //     if ($currentValue !== null) {
        //         $newValue = max(0, $currentValue - $payment);
        //         DB::table($table)
        //             ->where('id', $typeId)
        //             ->update([$column => $newValue]);
        //     }
        // }
        // Now, store the data in the receipt table
        // $receipt = Receipt::create([
        //     'receipt_id' => $request->receipt_id,
        //     'user_id' => $request->user_id,
        //     'date' => Carbon::createFromFormat('d-m-Y', $request->receipt_date)->format('Y-m-d'), // Convert to Y-m-d format
        //     'deduction_way' => $request->deduction_way,
        // ]);

        // Store the details in the receipt_details table
        // foreach ($request->type_id as $index => $typeId) {
        //     ReceiptDetail::create([
        //         'receipt_id' => $receipt->id, // Link receipt details to the saved receipt
        //         'type_id' => $typeId,
        //         'amount' => (float) $request->payment[$index],
        //         'payment' => $request->payment[$index],
        //         'description' => $request->description[$index] ?? '', // Default to empty string if no description
        //     ]);
        // }
        return redirect()->back()->with('success', 'Receipt  saved successfully!');
    }
    public function getUserFineBoth(Request $request)
    {
        $userId = $request->input('user_id');

        // Fetch fines
        // $fines = DB::table('fines')
        //     ->where('user_id', $userId)
        //     ->where('amount', '!=', 0)
        //     ->select('id', 'amount', 'note', DB::raw("'Fine' as type"))
        //     ->get();
        $fines = DB::table('fines')
            ->join('assets', 'fines.asset_id', '=', 'assets.id')
            ->where('fines.user_id', $userId) // Prefix the table for clarity
            ->where('fines.amount', '!=', 0) // Prefix the table to avoid ambiguity
            ->select(
                'fines.id as id', // Explicitly select the id column from fines and alias it
                'fines.amount as amount',
                'fines.note as note',
                DB::raw("'Fine' as type"), // Type column is created with a raw value
                'assets.asset_tag as tag' // Select asset_tag from the assets table
            )
            ->get();


        //Fetch deductions
        $deductions = DB::table('deductions')
            ->where('user_id', $userId)
            ->where('amount', '!=', 0)
            ->select('id', 'amount', 'note', DB::raw("'Deduction' as type"))
            ->get();
        // Fetch accidents
        // $accidents = DB::table('accidents')
        //     ->where('user_id', $userId)
        //     ->where('responsibility_amount', '!=', 0)
        //     ->select('id', 'responsibility_amount as amount', 'note', DB::raw("'Accident' as type"))
        //     ->get();
        $accidents = DB::table('accidents')
            ->join('assets', 'accidents.asset_id', '=', 'assets.id')
            ->where('accidents.user_id', $userId) // Explicitly specify the table for clarity
            ->where('accidents.responsibility_amount', '!=', 0)
            ->select(
                'accidents.id as id', // Alias the `id` column from the accidents table
                'accidents.responsibility_amount as amount',
                'accidents.note as note',
                DB::raw("'Accident' as type"),
                'assets.asset_tag as tag'
            )
            ->get();


        // Merge all three datasets
        $details = $fines->merge($deductions)->merge($accidents);

        // Calculate the total fine (sum of all amounts)
        $totalFine = $details->sum('amount');

        return response()->json([
            'total_fine' => $totalFine,
            'details' => $details,
        ]);
    }


    public function payableByDrivers()
    {
        $group_id = [2, 3];
        $users = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->whereIn('users_groups.group_id', $group_id)
            ->select('users.id', 'users.username')
            ->get();
        return view('layouts/regrid/payable_drivers')->with('users', $users);
    }
    public function index()
    {

        $this->authorize('view', Accident::class);
        return view('accidents.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->authorize('create', Accident::class);
        $assets = Asset::all()->pluck('asset_tag', 'id')->toArray();
        $group_id = [2, 3];
        $users = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->whereIn('users_groups.group_id', $group_id)
            ->select('users.id', 'users.username')
            ->get()
            ->pluck('username', 'id')
            ->toArray();
        $fine_type = AccidentType::all()->pluck('name', 'id')->toArray();
        $location = Location::all()->pluck('name', 'id')->toArray();
        // dd($assets);
        return view('accidents.edit', compact('assets', 'fine_type', 'location', 'users'));
    }
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            // dd($data);
            if ($data['claimable'] == 1) {
                // $data['responsibility_amount'] = $request->input('claim_opening') + $request->input('damages_amount');
                $data['responsibility_amount'] = $request->input('claim_opening');
            } else {
                // If claimable is not 1, set responsibility_amount to claim_opening
                // $data['responsibility_amount'] = $request->input('claim_opening');
                $data['responsibility_amount'] = $request->input('claim_opening') + $request->input('damages_amount');
            }

            // Initialize $imagePaths as an empty array to store image paths
            $imagePaths = [];

            // Check if there are images to upload
            if ($request->hasFile('accident_image')) {
                // Handle multiple image uploads
                foreach ($request->file('accident_image') as $image) {
                    // Generate a unique name for each image
                    $imageName = time() . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                    $image_resize = Image::make($image->getRealPath());
                    $image_resize->resize(1000, 1000);  // Resize image
                    $path = 'uploads/accidents/' . $imageName;
                    $image_resize->save($path);

                    // Store the image path in the array
                    $imagePaths[] = $path;
                }

                // Save image paths as a comma-separated string
                if (!empty($imagePaths)) {
                    $data['accident_image'] = implode(',', $imagePaths);
                }
            }
            if ($request->hasFile('relevant_files')) {
                foreach ($request->file('relevant_files') as $file) {
                    $fileName = time() . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                    $path = 'uploads/accidents/files/' . $fileName;
                    $file->move('uploads/accidents/files', $fileName);
                    $filePaths[] = $path;
                }

                if (!empty($filePaths)) {
                    $data['relevant_files'] = implode(',', $filePaths);
                }
            }
            // Create the accident record with the image path(s)
            $accident = Accident::create($data);

            // Check if accident creation was successful
            if (!$accident) {
                throw new \Exception('Failed to create accident record');
            }

            return redirect()->route('accidents')->with('success', 'Accident created successfully!');
        } catch (\Exception $e) {
            \Log::error('Accident creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create accident record: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Accident::find($id);
        $AccidentImages = explode(',', $data->accident_image);
        $RelevantFiles = explode(',', $data->relevant_files);
        $RelevantFileNumbers = array_map(function ($file) {
            $filename = basename($file);
            return pathinfo($filename, PATHINFO_FILENAME);
        }, $RelevantFiles);
        return view('accidents.show', compact('data', 'AccidentImages', 'RelevantFiles', 'RelevantFileNumbers'));
        // dd($data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Accident::class);
        $fine = Accident::find($id);

        $assets = Asset::all()->pluck('asset_tag', 'id')->toArray();

        $users = User::all()->pluck('username', 'id')->toArray();
        $fine_type = AccidentType::all()->pluck('name', 'id')->toArray();
        $location = Location::all()->pluck('name', 'id')->toArray();

        return view('accidents.edit', compact('fine', 'assets', 'users', 'fine_type', 'location'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $user = Accident::find($id);

        // Check if the user exists
        if ($user) {
            $responsibilityAmountSum = 0;
            if ($request->claimable == 1) {
                $responsibilityAmountSum = $request->claim_opening + $request->damages_amount;
            } else {
                // If claimable is not 1, set responsibility_amount to claim_opening
                $responsibilityAmountSum = $request->claim_opening;
            }
            // Handle image upload if a new image is provided

            if ($request->hasFile('accident_image')) {
                // Initialize an empty array to hold the new image paths
                $imagePaths = [];

                // If the user already has images, get the existing ones
                if ($user->accident_image) {
                    // Split the comma-separated string of existing images
                    $imagePaths = explode(',', $user->accident_image);
                }

                // Loop through each uploaded image and process them
                foreach ($request->file('accident_image') as $image) {
                    $imageName = time() . rand(100, 999) . '.' . $image->getClientOriginalExtension();
                    $image_resize = Image::make($image->getRealPath());

                    // Resize the image to a fixed size
                    $image_resize->resize(1000, 1000);

                    // Save the image to the uploads directory
                    $path = 'uploads/accidents/' . $imageName;
                    $image_resize->save(public_path($path));

                    // Add the image path to the array
                    $imagePaths[] = $path;
                }

                // Join the array of image paths into a comma-separated string
                $user->accident_image = implode(',', $imagePaths);
            }
            if ($request->hasFile('relevant_files')) {
                // Initialize an empty array to hold the new file paths
                $filePaths = [];

                // If the user already has files, get the existing ones
                if ($user->relevant_files) {
                    // Split the comma-separated string of existing files
                    $filePaths = explode(',', $user->relevant_files);
                }

                // Loop through each uploaded file and process them
                foreach ($request->file('relevant_files') as $file) {
                    // Generate a unique filename with original extension
                    $fileName = time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();

                    // Define the storage path
                    $path = 'uploads/accidents/' . $fileName;

                    // Store the file
                    $file->move(public_path('uploads/accidents'), $fileName);

                    // Add the file path to the array
                    $filePaths[] = $path;
                }

                // Join the array of file paths into a comma-separated string
                $user->relevant_files = implode(',', $filePaths);
            }
            // Update other fields
            $user->accident_number = $request->accident_number;
            $user->user_id = $request->user_id;
            $user->asset_id = $request->asset_id;
            $user->location = $request->location;
            $user->accident_date = $request->accident_date;
            $user->responsibility_amount = $responsibilityAmountSum;
            $user->recieved_by_user = $request->recieved_by_user;
            $user->note = $request->note;

            $user->save();

            return redirect()->route('accidents')->with('success', 'Data updated successfully');
        }

        return redirect()->route('accidents')->with('error', 'Accident not found');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('delete', Accident::class);
        $data = Accident::find($id);
        $data->delete();
        return redirect()->route('accidents')->with('success', 'data deleted successfully');
    }
    public function getFineTypeAmount(Request $request)
    {
        $fineTypeId = $request->fine_type_id;
        $fineType = AccidentType::find($fineTypeId);
        if ($fineType) {
            return response()->json(['amount' => $fineType->amount]);
        } else {
            return response()->json(['amount' => null]);
        }
    }

    // public function fetchAccidents(Request $request)
    // {
    //     $Date = $request->input('accident_date');
    //     $assetId = $request->input('asset_id');
    //     $fineDate = Carbon::parse($Date)->format('Y-m-d H:i:s');
    //     $asset = Asset::where('last_checkout', $fineDate)
    //         ->where('id', $assetId)
    //         ->first();
    //     if ($asset) {
    //         $userId = $asset->user_id;
    //         $user = User::where('id', $userId)
    //             ->select('id', 'username')
    //             ->first();
    //         return response()->json([
    //             'success' => true,
    //             'message' => $user
    //         ]);
    //     } else {
    //         $users = User::all()->pluck('username', 'id')->toArray();
    //         return response()->json([

    //             'success' => false,
    //             'message' => 'There is no user for Selected datetime.',
    //             'users' => $users
    //         ]);
    //     }
    // }
    public function fetchAccidents(Request $request)
    {
        $Date = $request->input('accident_date');
        $assetId = $request->input('asset_id');

        // Parse the input date without adding seconds
        $fineDate = Carbon::parse($Date)->format('Y-m-d H:i');

        // Query to match the `last_checkout` ignoring seconds
        $asset = Asset::where('id', $assetId)
            ->whereRaw("DATE_FORMAT(last_checkout, '%Y-%m-%d %H:%i') = ?", [$fineDate])
            ->first();

        if ($asset) {
            $userId = $asset->user_id;
            $user = User::where('id', $userId)
                ->select('id', 'username')
                ->first();

            return response()->json([
                'success' => true,
                'message' => $user,
            ]);
        } else {
            $users = User::all()->pluck('username', 'id')->toArray();

            return response()->json([
                'success' => false,
                'message' => 'There is no user for Selected datetime.',
                'users' => $users,
            ]);
        }
    }
}
