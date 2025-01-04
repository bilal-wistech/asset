<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeOfExpence;

class TypeOfExpenceController extends Controller
{
    public function index()
    {
        $this->authorize('view',TypeOfExpence::class);
        return view('type_of_expence/index')->with('type' , TypeOfExpence::all());

    }
    public function create()
    { 
        $this->authorize('create',TypeOfExpence::class);
        return view('type_of_expence/edit')->with('item', new TypeOfExpence);

    }
    public function update($id)
    { 
        $this->authorize('edit',TypeOfExpence::class);
        if (is_null($item = TypeOfExpence::find($id))) {
            return redirect()->route('type_of_expence.index')->with('error', 'Type does not exist!');
        }

        return view('type_of_expence/edit', compact('item'));

    }

    public function updateData(Request $request,$id)
    { 
        if (is_null($item = TypeOfExpence::find($id))) {
            return redirect()->route('type_of_expence.index')->with('error', 'Type does not exist!');
        }
        $item->title = $request->name;
        if ($item->save()) {
            // Redirect to the new category page
            return redirect()->route('expence.index')->with('success', 'Type is successfully updated!');
        }
        // The given data did not pass validation
        return redirect()->back()->withInput()->withErrors($item->getErrors());
        

    }

    public function store(Request $request)
    {
        $this->authorize('create', TypeOfExpence::class);
        $type = new TypeOfExpence;
        $type->title = $request->input('name');
        if ($type->save()) {
            return redirect()->route('expence.index')->with('success', 'Type is added successfully');
        }

        return redirect()->back()->withInput()->withErrors($type->getErrors());

    }

    public function delete($id)
    {
        $this->authorize('delete', TypeOfExpence::class);
        // Check if the category exists
        if (is_null($item = TypeOfExpence::findOrFail($id))) {
            return  redirect()->route('expence.index')->with('error', 'There is error in deleting Type');
        }
    
        $item->delete();
        // Redirect to the locations management page
        return redirect()->route('expence.index')->with('success', ' Type is deleted successfully');

    }


    
}
