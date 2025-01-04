<?php

namespace App\Http\Controllers\Assets;

use App\Events\CheckoutableCheckedIn;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssetCheckinRequest;
use App\Models\Asset;
use App\Models\CheckoutAcceptance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB; // Import the DB facade
use App\Models\Actionlog;

class AssetCheckinController extends Controller
{
    /**
     * Returns a view that presents a form to check an asset back into inventory.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param int $assetId
     * @param string $backto
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @since [v1.0]
     */
    public function create($assetId, $backto = null)
    {
        // Check if the asset exists
        if (is_null($asset = Asset::find($assetId))) {
            // Redirect to the asset management page with error
            return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.does_not_exist'));
        }

        $this->authorize('checkin', $asset);

        return view('hardware/checkin', compact('asset'))->with('statusLabel_list', Helper::statusLabelList())->with('backto', $backto);
    }
    
    
        public function store(AssetCheckinRequest $request, $assetId = null, $backto = null)
    {
        // dd($request->all());
        // Check if the asset exists
        if (is_null($asset = Asset::find($assetId))) {
            // Redirect to the asset management page with error
            return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.does_not_exist'));
        }

        if (is_null($target = $asset->last_checkout)) {
            return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.checkin.already_checked_in'));
        }
        $this->validate($request, [
            'location_id' => 'required',
            // other validation rules...
        ]);
        $this->authorize('checkin', $asset);

        $checkout_user_id = $asset->assigned_to;

        $checkin_counter = $asset->checkin_counter+1;

        $asset->expected_checkin = null;
        $asset->last_checkout = null;
        $asset->assigned_to = null;
        $asset->assignedTo()->disassociate($asset);
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

        if(!empty($asset->licenseseats->all())){
            foreach ($asset->licenseseats as $seat){
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
        $acceptances->map(function($acceptance) {
            $acceptance->delete();
        });

        // Was the asset updated?
        if ($asset->save()) {

            //save logs
        // if(isset($asset->getCheckOutInfo->getCheckOutUsersInfo) && $asset->getCheckOutInfo->getCheckOutUsersInfo<>null){
        //         foreach($asset->getCheckOutInfo->getCheckOutUsersInfo as $userArr){
                    $a_log                   = new Actionlog;
                    $a_log->user_id          = Auth::user()->id;
                    $a_log->action_type      = 'checkin from';
                    $a_log->target_id        = $checkout_user_id;
                    $a_log->target_type      = 'App\Models\User';
                    $a_log->note             = $request->note;
                    $a_log->item_type        = 'App\Models\Asset';
                    $a_log->item_id          = $asset->id;
                    $a_log->action_date      = date('Y-m-d H:m:i');
                    $a_log->save();
            //     }
            // }
            //update checin date in checkout_asset_user table
            DB::table('checkout_asset_user')
            ->where(['asset_id' => $asset->id, 'checkin_date'=>null])
            ->update([
                'checkin_date' => date("Y-m-d H:m:i"),
            ]);

            // event(new CheckoutableCheckedIn($asset, $target, Auth::user(), $request->input('note'), $checkin_at));

            if ((isset($user)) && ($backto == 'user')) {
                return redirect()->route('users.show', $user->id)->with('success', trans('admin/hardware/message.checkin.success'));
            }

            return redirect()->route('hardware.index')->with('success', trans('admin/hardware/message.checkin.success'));
        }
        // Redirect to the asset management page with error
        return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.checkin.error').$asset->getErrors());
    }

    /**
     * Validate and process the form data to check an asset back into inventory.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param AssetCheckinRequest $request
     * @param int $assetId
     * @param null $backto
     * @return Redirect
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @since [v1.0]
     */
    public function storeOld(AssetCheckinRequest $request, $assetId = null, $backto = null)
    {
        // Check if the asset exists
        if (is_null($asset = Asset::find($assetId))) {
            // Redirect to the asset management page with error
            return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.does_not_exist'));
        }

        if (is_null($target = $asset->assignedTo)) {
            return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.checkin.already_checked_in'));
        }
        $this->authorize('checkin', $asset);

        if ($asset->assignedType() == Asset::USER) {
            $user = $asset->assignedTo;
        }

        $asset->expected_checkin = null;
        $asset->last_checkout = null;
        $asset->assigned_to = null;
        $asset->assignedTo()->disassociate($asset);
        $asset->assigned_type = null;
        $asset->accepted = null;
        $asset->name = $request->get('name');



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

        if(!empty($asset->licenseseats->all())){
            foreach ($asset->licenseseats as $seat){
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
        $acceptances->map(function($acceptance) {
            $acceptance->delete();
        });

        // Was the asset updated?
        if ($asset->save()) {
            event(new CheckoutableCheckedIn($asset, $target, Auth::user(), $request->input('note'), $checkin_at));

            if ((isset($user)) && ($backto == 'user')) {
                return redirect()->route('users.show', $user->id)->with('success', trans('admin/hardware/message.checkin.success'));
            }

            return redirect()->route('hardware.index')->with('success', trans('admin/hardware/message.checkin.success'));
        }
        // Redirect to the asset management page with error
        return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.checkin.error').$asset->getErrors());
    }
}
