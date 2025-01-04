<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Models\AllowedDrivers;
use Illuminate\Http\Request;
use app\models\Asset;
use app\models\User;
use app\models\Setting;
use App\Models\Actionlog;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Show the page
        $this->authorize('view', Insurance::class);

        return view('insurance/index',compact('request'));
    }
    public function updateFailedTowing(Request $request)
{
    $Id = $request->input('asset_id');

    DB::table('towings_requests')
        ->where('id', $Id)
        ->update(['failed_towing' => 0]);
        $assetId = DB::table('towings_requests')->where('id', $Id)->value('asset_id');

    if ($assetId !== null) {
        DB::table('insurance')
            ->where('asset_id', $Id)
            ->increment('towingsavailable');
    } else {
        
    }

    return response()->json(['message' => 'Towing request updated successfully!']);
}

    public function updatenotification(Request $request)
    {
        if ($request->update) {
            DB::table('insurance')
                ->where('notification', 1)
                ->update(['notification' => 0]);
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false]);
    }
    
    public function showDetail(Request $request , $ins_id)
        {
            // dd('hello');
        // Show the page
        $ins = Insurance::withTrashed()->find($ins_id);
        // dd($ins);
        $asset = $ins->asset;
  
     
        // dd($asset->id);
        // $asset->uploads;
        $queryToGetUploads  = Actionlog::where('item_id' ,  $ins->id) 
        ->where('item_type', '=', 'App\Models\Asset')
        ->where('action_type', '=', 'uploaded')
        ->whereNotNull('filename')
        ->orderBy('created_at', 'desc')->get();
        //  dd($queryToGetUploads);
        // dd($query);
           if($asset->latestInsurance!=null && $asset->latestInsurance->drivers!=null){
            $allowedDriverIds = $asset->latestInsurance->drivers->pluck('driver_name')->toArray();
            $allowed_drivers = Helper::getUsersNames($allowedDriverIds);
           }
           
            return view('insurance/detail', compact('ins' , 'allowed_drivers' , 'asset' , 'queryToGetUploads'));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $this->authorize('create', Insurance::class);
        $item = new Insurance;
        $item->insurance_date = date('Y-m-d');
        $user = User::all();
        $toggle_value = DB::table('assets')->where('id', $item->asset_id)->value('toggle');

        return view('insurance/edit')->with('item', new Insurance)
        ->with('assets', Helper::getAssetsArr())
        ->with('suppliers', Helper::getSuppliersArr())
        ->with('user', $user)
        ->with('toggle_value', $toggle_value);
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
        $time = date("H:i:s");
        $model = new Insurance;
        $model->asset_id = $request->asset_id;
        $model->vendor_id = $request->vendor_id;
        $model->recovery_number = $request->recovery_number;
        $model->towingsavailable = $request->towingsavailable;
        $model->insurance_date = $request->insurance_date." ".$time;
        $model->insurance_from = $request->insurance_from." ".$time;
        $model->insurance_to = $request->insurance_to;
        $model->amount = $request->amount;
        $model->premium_type = $request->premium_type;
        $model->cost = $request->cost;
        $model->no_of_drivers_allowed = $request->no_of_drivers_allowed;
        $model->driver_cost = $request->driver_cost;
        if($model->save()){
            if(isset($request->drivers) && $request->drivers<>null){
                // dd($request->drivers);
                foreach($request->drivers as $key=>$driver){
                    $child = new AllowedDrivers;
                    $child->insurance_id = $model->id;
                    $child->driver_name = $driver['name'];
                    $child->save();
                }
            } 
            return redirect()->route('insurance.index')->with('success', trans('admin/insurance/message.create.success'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function show(Insurance $insurance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Insurance  $insurance
     * @return \Illumi                                 233wqsanate\Http\Response
     */
    public function edit($insurance_id = null)
    {
        $this->authorize('update', Insurance::class);
       
        if (is_null($item = Insurance::find($insurance_id))) {
            return redirect()->route('insurance.index')->with('error', trans('admin/insurance/message.does_not_exist'));
        }
        $toggle_value = DB::table('assets')->where('id', $item->asset_id)->value('toggle');
        $user = User::all();
        //dd($toggle_value);

        return view('insurance/edit', compact('item' , 'user','toggle_value'))
        ->with('assets', Helper::getAssetsArr())
        ->with('suppliers', Helper::getSuppliersArr());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $insurance_id = null)
    {
        //dd($request->all());
        if (is_null($model = Insurance::find($insurance_id))) {
            // Redirect to the insurance management page
            return redirect()->route('insurance.index')->with('error', trans('admin/insurance/message.does_not_exist'));
        }

        //dd('hello');

        $time = date("H:i:s");
        $model->asset_id = $request->asset_id;
        $model->vendor_id = $request->vendor_id;
        $model->towingsavailable = $request->towingsavailable;
        $model->recovery_number = $request->recovery_number;
        $model->ins_id = $request->ins_id;   
        $model->user_id = $request->user_id;
        $model->insurance_date = $request->insurance_date." ".$time;
        $model->insurance_from = $request->insurance_from." ".$time;
        $model->insurance_to = $request->insurance_to;
        $model->amount = $request->amount;
        $model->premium_type = $request->premium_type;
        $model->cost = $request->cost;
        $model->no_of_drivers_allowed = $request->no_of_drivers_allowed;
        $model->driver_cost = $request->driver_cost;
        if($model->save()){
            if(isset($request->drivers) && $request->drivers<>null){
                AllowedDrivers::where(['insurance_id'=>$insurance_id])->delete();
                foreach($request->drivers as $key=>$driver){
                    $child = new AllowedDrivers;
                    $child->insurance_id = $model->id;
                    $child->driver_name = $driver['name'];
                    $child->save();
                }
            }
            
            return redirect()->route('insurance.index')->with('success', trans('admin/insurance/message.create.success'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function destroy($insurance_id = null)
    {
        
        $this->authorize('delete', Insurance::class);
        $data = Insurance::find($insurance_id);
        $data->delete();
        // Check if the category exists
        // if (is_null($insurance = Insurance::findOrFail($insurance_id))) {
        //     return redirect()->route('insurance.index')->with('error', trans('admin/insurance/message.not_found'));
        // }

        // if (! $insurance->isDeletable()) {
        //     return redirect()->route('insurance.index')->with('error', trans('admin/insurance/message.notdeleteable'));
        // }

        // $insurance->delete();
        // AllowedDrivers::where(['insurance_id'=>$insurance_id])->delete();
        // Redirect to the locations management page
        return redirect()->route('insurance.index')->with('success', 'data deleted successfully');
    }

    

    public function toggleAllDrivers(Request $request)
{
    $asset_id = $request->input('asset_id');
    $all_drivers_toggle = $request->input('all_drivers_toggle');
    if ($all_drivers_toggle == "1") {
        Asset::where('id', $asset_id)
        ->update(['toggle' => $all_drivers_toggle]);
    } elseif ($all_drivers_toggle == "0") {
        Asset::where('id', $asset_id)
            ->update(['toggle' => null]);
    }
    return response()->json(['status' => 'success', 'toggle' => $all_drivers_toggle,'success' => 'All driver assigned successfully']);
}


    

    public function getDriverField()
    {
        $users = Helper::getDriverNamesArr();
        $driver_row = request()['data']['driver_row'];
        $field = \Form::select('drivers['.$driver_row.'][name]', $users, null, ['class' => 'form-control', 'id' => 'serchable-'.$driver_row.'', 'placeholder' => 'select driver']) ;


        $data = '<tr id="driver-row-'.$driver_row.'">
            <th scope="row">'.$driver_row.'</th>
            <td>
                <div class="form-group ">
                    <div class="col-md-12">
                        <div class="col-md-12" style="padding-left:0px">
                            '.$field.'
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <button type="button" data-rid="'.$driver_row.'"
                    class="btn btn-danger pull-right remove-driver"><i class="fas fa-icon icon-white"
                        aria-hidden="true"></i>Remove</button>
            </td>
        </tr>';

        return response()->json($data);

    }
}
