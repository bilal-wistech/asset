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
       

        try {
            $store_expense = new AddExpence;
            $store_expense->total_milage = $request->meter;
            $store_expense->amount = $request->amount;
            $store_expense->asset_id = $request->asset_id;
            $store_expense->type_id = $request->type_id;
            $store_expense->pump_station = $request->pump_station;
            $store_expense->user_id = Auth::guard('api')->user()->id;

            // Handle file upload
            if ($request->file('file')) {
                $image = $request->file('file');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(1000, 1000);

                $path = 'uploads/' . $imageName;
                $image_resize->save($path);

                $store_expense->image = $path;
            }
            // Handle base64 image
            elseif ($request->has('image') && is_string($request->image)) {
                $imageData = $request->image;

                // Check if the image contains data URI scheme
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                    $imageType = $matches[1];
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $decodedImage = base64_decode($imageData);

                    if ($decodedImage === false) {
                        throw new \Exception('Failed to decode base64 string');
                    }

                    $imageName = time() . '.' . $imageType;
                    $path = 'uploads/' . $imageName;

                    $img = Image::make($decodedImage);
                    $img->resize(1000, 1000);
                    $img->save($path);

                    $store_expense->image = $path;
                }
            }

            if ($store_expense->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Expense created successfully',
                    'data' => $store_expense
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create expense'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create expense:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create expense',
                'error' => $e->getMessage()
            ], 500);
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
