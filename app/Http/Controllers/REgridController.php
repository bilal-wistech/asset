<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AddExpence;
use Illuminate\Http\Request;
use App\Models\TowingRequest;
use App\Exports\ReimmensibleExport;
use Maatwebsite\Excel\Facades\Excel;

class REgridController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function FuelExpenseReport(){
        $users = User::all();
        return view('layouts/regrid/fuelexpense')->with('users', $users);
    }
    public function show()

    {
       
        //$this->authorize('view', AddExpence::class);
        $users = User::all();
        return view('layouts/regrid/show')->with('users', $users);
        
    }
    public function show_towing_requests()

    {
        $this->authorize('view', TowingRequest::class);
        $users = User::all();
        return view('layouts/regrid/towingrequest')->with('users', $users);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function export(Request $request)
    {
        
        $user_id = 0;

        $query = AddExpence::select('add_expences.*')->with('type', 'asset', 'userData');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        if ($request->query('user_id') && $request->query('user_id') > 0) {
            $user_id = $request->query('user_id');
        }

        if ($user_id) {
            $query = $query->where('user_id', $user_id);
        }
        $expenses = $query->get();
        // Generate and return Excel download response
        return Excel::download(new ReimmensibleExport($expenses), 'Reimmensible-Expense.xlsx');
    }
    
}
