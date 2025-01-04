<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Transformers\HandoverDetailsTransformer;
use App\Models\Actionlog;
use App\Models\Asset;
use App\Models\CheckinReason;
use App\Models\CheckoutAcceptance;
use App\Models\HandoverImages;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HandoverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with(
            'assets',
            'assets.model',
            'assets.model.fieldset.fields',
            'consumables',
            'accessories',
            'licenses',
        )->find(Auth::user()->id);
//        dd($user->assets);
        foreach ($user->assets as $asset) {
            //  dd($asset);
            if ($asset) {
                $assets[] = $asset->id.':'.$asset->name.'( '.$asset->asset_tag.')';
            }
        }
        // $assets =  Helper::getAssetsArr();

        $reason = CheckinReason::all();
        return response()->json([
            'assets' => $assets,
            'reason' => $reason
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = $request->all();
//        dd($request->all());
        $assetId = $request->asset_id;
        // dd($assetId);
        $asset = Asset::find($assetId);
        $checkout_user_id = $asset->assigned_to;

        $checkin_counter = $asset->checkin_counter + 1;

        $asset->expected_checkin = null;
        $asset->last_checkout = null;
        $asset->assigned_to = null;
        $asset->assignedTo()->disassociate($asset);
        // dd($data);
        $asset->assigned_type = null;
        $asset->accepted = null;
        $asset->name = $request->get('name');
        $asset->checkin_counter = $checkin_counter;


        if ($request->filled('status_id')) {
            $asset->status_id = e($request->get('status_id'));
        }


        // This is just meant to correct legacy issues where some user data would have 0
        // as a location ID, which isn't valid. Later versions of Snipe-IT have stricter validation
        // rules, so it's necessary to fix this for long-time users. It's kinda gross, but will help
        // people (and their data) in the long run

        if ($asset->rtd_location_id == '0') {
            \Log::debug('Manually override the RTD location IDs');
            \Log::debug('Original RTD Location ID: '.$asset->rtd_location_id);
            $asset->rtd_location_id = '';
            \Log::debug('New RTD Location ID: '.$asset->rtd_location_id);
        }

        if ($asset->location_id == '0') {
            \Log::debug('Manually override the location IDs');
            \Log::debug('Original Location ID: '.$asset->location_id);
            $asset->location_id = '';
            \Log::debug('New RTD Location ID: '.$asset->location_id);
        }

        $asset->location_id = $asset->rtd_location_id;

        if ($request->filled('location_id')) {
            \Log::debug('NEW Location ID: '.$request->get('location_id'));
            $asset->location_id = e($request->get('location_id'));
        }

        $checkin_at = date('Y-m-d H:i:s');
        if (($request->filled('checkin_at')) && ($request->get('checkin_at') != date('Y-m-d'))) {
            $checkin_at = $request->get('checkin_at');
        }

        if (!empty($asset->licenseseats->all())) {
            foreach ($asset->licenseseats as $seat) {
                $seat->assigned_to = null;
                $seat->save();
            }
        }

        // Get all pending Acceptances for this asset and delete them
        $acceptances = CheckoutAcceptance::pending()->whereHasMorph('checkoutable',
            [Asset::class],
            function (Builder $query) use ($asset) {
                $query->where('id', $asset->id);
            })->get();
        $acceptances->map(function ($acceptance) {
            $acceptance->delete();
        });
        $requestData = new HandoverImages;
        // return $input;
        $images = array();
        if ($files = $request->file('images')) {
            foreach ($files as $file) {
//                dd($files);
                $requestData = new HandoverImages;
                $name = $file->getClientOriginalName();
                $file->move('images', $name);
                $images[] = $name;
                $filePath = 'images/'.$name;
                $requestData->images = $filePath;


                $requestData->asset_id = $assetId;
                $requestData->notes = $request->note;
                $requestData->reason_id = $request->reason_id;
                $requestData->checkin_date = $request->checkin_at;
                $data = $requestData->save();
            }
        } else {

            $requestData->asset_id = $assetId;
            $requestData->notes = $request->note;
            $requestData->reason_id = $request->reason_id;
            $requestData->checkin_date = $request->checkin_at;
            $data = $requestData->save();

        }

        // Was the asset updated?
        if ($asset->save()) {

            //save logs

            $a_log = new Actionlog;
            $a_log->user_id = Auth::user()->id;
            $a_log->action_type = 'checkin from';
            $a_log->target_id = $checkout_user_id;
            $a_log->target_type = 'App\Models\User';
            $a_log->note = $request->note;
            $a_log->item_type = 'App\Models\Asset';
            $a_log->item_id = $asset->id;
            $a_log->action_date = date('Y-m-d H:m:i');
            $a_log->save();


            //update checin date in checkout_asset_user table
            DB::table('checkout_asset_user')
                ->where(['asset_id' => $asset->id, 'checkin_date' => null])
                ->update([
                    'checkin_date' => date("Y-m-d H:m:i"),
                ]);


            return response()->json([
                'message' => 'Asset checked in successfully.',


            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $assetId = $request->asset_id;

        $asset = Asset::where(['id' => $assetId])->first();
        $checkout_user_id = $asset->assigned_to;


        $requestData = new HandoverImages;
        // return $input;
        $images = array();
        if ($files = $request->file('images')) {
            foreach ($files as $file) {
                $requestData = new HandoverImages;
                $name = $file->getClientOriginalName();
                $file->move('images', $name);
                $images[] = $name;
                $filePath = 'images/'.$name;
                $requestData->images = $filePath;


                $requestData->asset_id = $assetId;
                $requestData->notes = $request->note;
                $requestData->reason_id = $request->reason_id;
                $requestData->checkin_date = $request->checkin_at;
                $data = $requestData->save();
            }
        } else {

            $requestData->asset_id = $assetId;
            $requestData->notes = $request->note;
            $requestData->reason_id = $request->reason_id;
            $requestData->checkin_date = $request->checkin_at;
            $data = $requestData->save();

        }

        // Get all pending Acceptances for this asset and delete them
        $acceptances = CheckoutAcceptance::pending()->whereHasMorph('checkoutable',
            [Asset::class],
            function (Builder $query) use ($asset) {
                $query->where('id', $asset->id);
            })->get();
        $acceptances->map(function ($acceptance) {
            $acceptance->delete();
        });


        //checkout to user

        if ($asset <> null) {
            $checkout_counter = $asset->checkout_counter + 1;
            $asset->last_checkout = date("Y-m-d H:m:s");
            $asset->assigned_type = "App\Models\User";
            $asset->assigned_to = $request->user_id;
            $asset->expected_checkin = $request->expected_checkin;
            $asset->checkout_counter = $checkout_counter;
            $asset->save();


            if (!empty($asset->licenseseats->all())) {
                if (request('checkout_to_type') == 'user') {
                    foreach ($asset->licenseseats as $seat) {
                        $seat->assigned_to = $target->id;
                        $seat->save();
                    }
                }
            }
        }

        if ($asset->save()) {

            //save logs

            $a_log = new Actionlog;
            $a_log->user_id = Auth::user()->id;
            $a_log->action_type = 'checkin from';
            $a_log->target_id = $checkout_user_id;
            $a_log->target_type = 'App\Models\User';
            $a_log->note = $request->note;
            $a_log->item_type = 'App\Models\Asset';
            $a_log->item_id = $asset->id;
            $a_log->action_date = date('Y-m-d H:m:i');
            $a_log->save();


            //update checin date in checkout_asset_user table
            DB::table('checkout_asset_user')
                ->where(['asset_id' => $asset->id, 'checkin_date' => null])
                ->update([
                    'checkin_date' => date("Y-m-d H:m:i"),
                ]);

            return response()->json([
                'message' => 'Asset checked in to user successfully.',


            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function users($id)
    {
        $asset_id = $id;
        $asset = Asset::where(['id' => $asset_id])->first();

        $allowedDriverIds = ($asset->latestInsurance && $asset->latestInsurance->drivers) ? $asset->latestInsurance->drivers->pluck('driver_name')->toArray() : [];
        // return $allowedDriverIds;
        if ($allowedDriverIds) {
            $users = Helper::getUsersNames($allowedDriverIds);

            return response()->json($users);
        } else {
            return response()->json(['message' => 'No users allowed yet']);
        }


    }

    public function handoverDetails(Request $request)
    {
        $this->authorize('view', HandoverImages::class);

        $handover_details = HandoverImages::select('handover_images.*');

        // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
        // case we override with the actual count, so we should return 0 items.
        $offset = $handover_details && $request->get('offset') > $handover_details->count() ? $handover_details->count() : $request->get('offset',
            0);

        // Check to make sure the limit is not higher than the max allowed
        config('app.max_results') >= $request->input('limit') && $request->filled('limit') ? ($limit = $request->input('limit')) : ($limit = config('app.max_results'));

        $total = $handover_details->count();
        $handover_details = $handover_details->skip($offset)->take($limit)->get();
//        dd($handover_details);
        return (new HandoverDetailsTransformer())->transformHandoverDetails($handover_details, $total);
    }
}
