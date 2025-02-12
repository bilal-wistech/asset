<?php

namespace App\Http\Controllers;
use App\Models\RidingCompany;
use Illuminate\Http\Request;

class RidingCompanyController extends Controller
{
    public function index(){
        return view('riding-companies.index');
    }
    public function create(){
        return view('riding-companies.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        RidingCompany::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('riding-companies.index');
    }

    public function edit($id)
    {
        // dd((int) $id);
    $riding_company = RidingCompany::findOrFail($id);
    // dd($riding_company);
        return view('riding-companies.edit', compact('riding_company'));
    }

    public function update(Request $request, $id){
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Find the company by ID
        $company = RidingCompany::findOrFail($id);

        // Update the company details
        $company->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Redirect back to the index page with a success message
        return redirect()->route('riding-companies.index')->with('success', 'Company updated successfully!');

    }

    public function destroy($id){
        // Find the company by ID
        $company = RidingCompany::findOrFail($id);

        // Delete the company
        $company->delete();

        // Redirect back to the index page with success message
        return redirect()->route('riding-companies.index');
    }

}
