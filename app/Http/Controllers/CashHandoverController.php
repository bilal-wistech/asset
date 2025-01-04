<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Receipt;
use App\Models\CashHandover;
use App\Models\CashHandoverReceipt;
use App\Models\CashHandoverVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashHandoverController extends Controller
{
    public function index()
    {
        return view('cash-handover.index');
    }
    public function create()
    {
        $users = User::all();
        return view('cash-handover.create', compact('users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'receipt_ids' => 'required|array',
            'receipt_ids.*' => 'required|integer',
            'total_amount' => 'required|numeric|min:0',
            'handover_by' => 'required|exists:users,id',
            'handover_to' => 'required|exists:users,id|different:handover_by',
        ]);

        DB::beginTransaction();
        try {
            // Create cash handover record
            $cashHandover = CashHandover::create([
                'total_amount' => $request->total_amount,
                'handover_by' => $request->handover_by,
                'handover_to' => $request->handover_to,
                'handover_date' => $request->handover_date,
            ]);

            // Attach receipts to the handover
            foreach ($request->receipt_ids as $receipt_id) {
                DB::table('cash_handover_receipts')->insert([
                    'cash_handover_id' => $cashHandover->id,
                    'receipt_id' => $receipt_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            // Update the status of the adjustments if needed
            Receipt::whereIn('id', $request->receipt_ids)
                ->update(['handed_over' => 1]);

            DB::commit();

            return response()->json([
                'message' => 'Cash handover created successfully',
                'data' => $cashHandover
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating cash handover',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function view($id)
    {
        $receipt_id = (int)$id;
        $receipt = Receipt::findOrFail($receipt_id);
        $cashHandover = CashHandoverReceipt::where('receipt_id', $receipt_id)->first();
        $handover = CashHandover::with(['receipts', 'handoverByUser', 'handoverToUser'])->findOrFail($cashHandover->cash_handover_id);
        return view('cash-handover.view', compact('handover', 'receipt'));
    }
    public function verification(Request $request)
    {
        $verification = CashHandoverVerification::create($request->all());
        CashHandover::where('id', $verification->cash_handover_id)
            ->update(['is_verified' => 1]);

        return redirect()->back()->with('success', 'Verification done successfully');
    }
}
