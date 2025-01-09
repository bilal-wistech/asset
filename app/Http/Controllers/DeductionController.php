<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Deduction;
use App\Models\Location;
use App\Models\ResponsibilityFine;
use App\Models\FineType;
use App\Models\DeductionType;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendPushNotification;
use Kutia\Larafirebase\Facades\Larafirebase;
use ExpoSDK\Expo;
use App\Models\ExpoToken;
use ExpoSDK\ExpoMessage;
use Image;
use DB;


class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {

        $this->authorize('view', Deduction::class);
        return view('deductions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deductionType(Request $request)
    {
        $this->authorize('view', DeductionType::class);

        if (!$request->filled('name')) {
            return response()->json(Helper::formatStandardApiResponse('error', null, ['Name' => ['Name is required.']]));
        }



        $type = new DeductionType;
        $type->name = $request->name;


        if ($type->save()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $type->id,
                    'name' => $type->name
                ],
                'message' => 'New Reason is Saved Successfully.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'There is an error in saving'
        ]);
    }
    public function create()
    {

        $this->authorize('create', Deduction::class);
        $assets = Asset::all()->pluck('asset_tag', 'id')->toArray();
        // $users = User::all()->pluck('username', 'id')->toArray();
        $group_id = [2, 3];
        $users = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->whereIn('users_groups.group_id', $group_id)
            ->select('users.id', 'users.username')
            ->get()
            ->pluck('username', 'id')
            ->toArray();

        // dd($users[0]);
        $fine_type = DeductionType::all()->pluck('name', 'id')->toArray();
        $location = Location::all()->pluck('name', 'id')->toArray();
        // dd($assets);
        return view('deductions.edit', compact('assets', 'fine_type', 'location', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        DB::table('deductions')->insert([
            'deduction_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $request->fine_date)->format('Y-m-d'),
            'reason' => $request->fine_type,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'note' => $request->note,

        ]);

        return redirect()->route('deductions')->with('success', 'Deduction created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Deduction::find($id);
        return view('deductions.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dd("ok");
        $this->authorize('update', Deduction::class);
        $fine = Deduction::find($id);

        $assets = Asset::all()->pluck('asset_tag', 'id')->toArray();

        $group_id = [2, 3];
        $users = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->whereIn('users_groups.group_id', $group_id)
            ->select('users.id', 'users.username')
            ->get()
            ->pluck('username', 'id')
            ->toArray();
        $fine_type = DeductionType::all()->pluck('name', 'id')->toArray();
        $location = Location::all()->pluck('name', 'id')->toArray();

        return view('deductions.edit', compact('fine', 'assets', 'users', 'fine_type', 'location'));
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


        $deduction = Deduction::find($id);

        if (!$deduction) {
            return redirect()->route('deductions')->with('error', 'Deduction not found.');
        }

        // Update the record
        $deduction->deduction_date = $request->fine_date; // Ensure the date format matches your database
        $deduction->reason = $request->fine_type;
        $deduction->user_id = $request->user_id;
        $deduction->amount = $request->amount;
        $deduction->note = $request->note;

        // Save the updated record
        $deduction->save();

        // Redirect back with success message
        return redirect()->route('deductions')->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('delete', Deduction::class);
        $data = Deduction::find($id);
        $data->delete();
        return redirect()->route('deductions')->with('success', 'data deleted successfully');
    }
}
