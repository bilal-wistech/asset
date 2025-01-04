<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\AssetAssignTo;
use Auth;
use DataTables;
use Illuminate\Http\Request;

class AssetAssignmentController extends Controller
{
    public function index()
    {
        $this->authorize('view', AssetAssignment::class);

        return view('asset-assignment/index');

    }

    public function create(Request $request)
    {


        $this->authorize('create', AssetAssignment::class);
        $item = new AssetAssignment;

        // dd(Helper::getAssetsArr());

        return view('asset-assignment/edit')->with('item', new AssetAssignment)
            ->with('assets', Helper::getReadyAssets())
            ->with('users', null)
            ->with('assigned_ids', null);

    }


    public function store(Request $request)
    {
        // dd($request->all());
        $model = new AssetAssignment;
        $model->asset_id = $request->asset_id;
        $model->assigned_at = date("Y-m-d H:i:s");
        $model->assigned_by = Auth::user()->id;

        if ($model->save()) {

            if (isset($request->user_id) && is_array($request->user_id) && $request->user_id <> null) {
                foreach ($request->user_id as $key => $userId) {
                    $child = new AssetAssignTo;
                    $child->assignment_id = $model->id;
                    $child->driver_id = $userId;
                    $child->save();
                }
            }

            return redirect()->route('asset-assignment')->with('success',
                trans('admin/asset-assignment/message.create.success'));
        }
    }


    public function show(AssetAssignment $assetAssignment)
    {
        //
    }

    public function edit($id = null)
    {
        $this->authorize('update', AssetAssignment::class);

        if (is_null($item = AssetAssignment::find($id))) {
            return redirect()->route('asset-assignment')->with('error',
                trans('admin/asset-assignment/message.does_not_exist'));
        }

        // $asset = Asset::where(['id'=>$asset_id])->first();

        $userIds = $item->asset->latestInsurance->drivers->pluck('driver_name')->toArray();
        $users = Helper::getUsersNames($userIds);

        $assigned_ids = $item->userIds->pluck('driver_id')->toArray();

        return view('asset-assignment/edit', compact('item', 'assigned_ids'))
            ->with('assets', Helper::getAssetsArr())
            ->with('users', $users);
    }


    public function update(Request $request, AssetAssignment $assetAssignment, $id)
    {
        $model = AssetAssignment::where(['id' => $id])->first();
        $model->asset_id = $request->asset_id;
        $model->updated_at = date("Y-m-d H:i:s");
        $model->updated_by = Auth::user()->id;

        if ($model->save()) {
            if (isset($request->user_id) && is_array($request->user_id) && $request->user_id <> null) {
                AssetAssignTo::where(['assignment_id' => $id])->delete();
                foreach ($request->user_id as $key => $userId) {
                    $child = new AssetAssignTo;
                    $child->assignment_id = $model->id;
                    $child->driver_id = $userId;
                    $child->save();
                }
            }

            return redirect()->route('asset-assignment')->with('success',
                trans('admin/asset-assignment/message.update.success'));
        }
    }


    public function destroy(AssetAssignment $assetAssignment)
    {
        //
    }


    public function getAllowedDrivers(Request $request)
    {
        $asset_id = $request->input()['data']['asset_id'];
        $asset = Asset::where(['id' => $asset_id])->first();

        $allowedDriverIds = $asset->latestInsurance->driverKeys->pluck('driver_name')->toArray();

        $users = getUsersNames($allowedDriverIds);

        return response()->json($users);

    }

    public function handoverDetails()
    {
        return view('asset-assignment.handover-details');
    }
}