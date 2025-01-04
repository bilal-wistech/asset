<?php

namespace App\Http\Controllers;
use App\Models\tsrepairoptions;
use Illuminate\Http\Request;

class tsRepairOptionsController extends Controller
{
    //
    public function index()
    {
        return view('tsrepairoptions/index')->with('name' , tsrepairoptions::all());
    }
    public function create()
    { 
        return view('tsrepairoptions/edit')->with('item', new tsrepairoptions);

    }
    public function update($id)
    { 
        if (is_null($item = tsrepairoptions::find($id))) {
            return redirect()->route('tsrepairoptions.index')->with('error', 'This Repair Option does not exist!');
        }

        return view('tsrepairoptions/edit', compact('item'));
    }
    public function updateData(Request $request,$id)
    { 
        if (is_null($item = tsrepairoptions::find($id))) {
            return redirect()->route('tsrepairoptions.index')->with('error', 'This Repair Option does not exist!');
        }
        $item->name = $request->name;
        if ($item->save()) {
            // Redirect to the new category page
            return redirect()->route('tsrepairoptions.index')->with('success', 'Repair Option is successfully updated!');
        }
        // The given data did not pass validation
        return redirect()->back()->withInput()->withErrors($item->getErrors());
    }
    public function store(Request $request)
    {
        $this->authorize('create', tsrepairoptions::class);
        $type = new tsrepairoptions;
        $type->name = $request->input('name');
        if ($type->save()) {
            return redirect()->route('tsrepairoptions.index')->with('success', 'Repair Option is added successfully');
        }
        return redirect()->back()->withInput()->withErrors($type->getErrors());
    }
    public function delete($id)
    {
        $this->authorize('delete', tsrepairoptions::class);
        // Check if the category exists
        if (is_null($item = tsrepairoptions::findOrFail($id))) {
            return  redirect()->route('tsrepairoptions.index')->with('error', 'There is error in deleting Option');
        }
        $item->delete();
        // Redirect to the locations management page
        return redirect()->route('tsrepairoptions.index')->with('success', 'Option is deleted successfully');
    }
}
