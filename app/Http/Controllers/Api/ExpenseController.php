<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AddExpence;
use App\Models\TypeOfExpence;
use App\Helpers\Helper;
use App\Models\User;
use Image;


class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  return 'hello';  
        $user = Auth::guard('api')->user();
        $response = AddExpence::with('type', 'asset')->where('user_id', $user->id)->get();
        
      
        return response($response, 200);
        
        

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $user = User::with(
            'assets',
            'assets.model',
            'assets.model.fieldset.fields',
            'consumables',
            'accessories',
            'licenses',
        )->find(Auth::user()->id);
        $assets = [];
        if($user->assets != null){
              foreach ($user->assets as $asset) {
             
            // return $asset;
            //   $assets[] ='"' .  $asset->id. '"'  . ':' . $asset->name . '('. $asset->asset_tag . ')';
              $name = isset($asset->name) ? $asset->name : $asset->asset_tag;
              $assets[$asset->id] = $name;//$asset->only('id', 'name', 'asset_tag');
            // $assets[] =  $asset->id . "''" . "'' : ''" .'(' . $asset->asset_tag . ')' . '"';
           

        }
        }
      
       $type = TypeOfExpence::all();
        
        $user_id = Auth::guard('api')->user()->id;
        //dd($user_id);
        //$user = Auth::user()->id;
        return response()->json([
            'item' => new AddExpence,
            'assets' => $assets,
            'type' => $type,
            'user' => $user_id
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
             
               $store_expence = new AddExpence;
               $store_expence->total_milage = $request->meter;
               $store_expence->amount = $request->amount;
               $store_expence->asset_id = $request->asset_id;
               $store_expence->type_id = $request->type_id;
                $store_expence->pump_station = $request->pump_station;
             
               $store_expence->user_id = Auth::guard('api')->user()->id;
       
              
              // $img = $request->file('file');
              if($request->file('file')){
               $image = $request->file('file');
               $imageName = time() . '.' . $image->getClientOriginalExtension();
               
               $image_resize = Image::make($image->getRealPath());
             
               $image_resize->resize(1000,1000);    
              // dd($image_resize);
               $path = 'uploads/' . $imageName;
                
               $image_resize->save($path);
               
               $imageUri = 'uploads/' . $imageName;  
       
               $store_expence->image = $path;
            //    return response()->json(['image' => $imageUri], 200);
              }
              if ($store_expence->save()) {
                return response()->json(['result' => 'Data is stored successfully'], 200);
            }
            else
              {
                return response()->json(['message' => 'There is an error in uploading'], 400);
              }
            //   return  $store_expence->image;
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
}
