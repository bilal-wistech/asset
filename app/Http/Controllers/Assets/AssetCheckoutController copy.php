<?php

namespace App\Http\Controllers\Assets;

use App\Models\Asset;
use App\Helpers\Helper;
use App\Models\Actionlog;
use Illuminate\Http\Request;
use App\Models\CheckoutAssetUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CheckoutAssignedUser;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\CheckoutNotAllowed;
use App\Http\Controllers\CheckInOutRequest;
use App\Http\Requests\AssetCheckoutRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AssetCheckoutController extends Controller
{
    use CheckInOutRequest;

    /**
     * Returns a view that presents a form to check an asset out to a
     * user.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param int $assetId
     * @since [v1.0]
     * @return View
     */
    public function createOld($assetId)
    {

        // Check if the asset exists
        if (is_null($asset = Asset::with('company')->find(e($assetId)))) {
            return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.does_not_exist'));
        }

        $this->authorize('checkout', $asset);

        if ($asset->availableForCheckout()) {
            return view('hardware/checkout', compact('asset'))
                ->with('statusLabel_list', Helper::deployableStatusLabelList());
        }

        return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.checkout.not_available'));
    }

    public function actionCreate($assetId)
{
    // Check if the asset exists
    if (is_null($asset = Asset::with('company')->find(e($assetId)))) {
        return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.does_not_exist'));
    }

    $group_id = [2, 3];
    $all_drivers_list = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
        ->whereIn('users_groups.group_id', $group_id)
        ->select('users.username as username', 'users.id as user_id')->get();
        //->toArray();
// dd($all_drivers_list[0]);
    $item = $asset;
    if (!empty($item->id)) {
        $insurance = DB::table('insurance')->where('asset_id', $item->id)->first();
    }

    $this->authorize('checkout', $asset);

    if ($asset->availableForCheckout()) {
        return view('hardware/custom-checkout', compact('asset', 'item', 'all_drivers_list', 'insurance'))
            ->with('statusLabel_list', Helper::deployableStatusLabelList());
    }

    return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.checkout.not_available'));
}



    public function actionStore(Request $request, $assetId)
    {
        // dd($request->all());
        $this->validate($request, [
            'location_id' => 'required',
            // other validation rules...
        ]);
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'user_id' => 'required',
                'checkout_at' => 'required',
                'expected_checkin' => 'required',
            ]);

            //my new code
            $model = new CheckoutAssetUser;
            $model->asset_id = $assetId;
            $model->handover_to = $request->user_id;
            $model->checkout_date = $request->checkout_at;
            $model->expected_checkin_date = $request->expected_checkin;
            $model->note = $request->note;
            $model->created_by = Auth::user()->id;
            if ($model->save()) {
                // if($request->user_id<>null){
                //     foreach($request->user_id as $user_id){
                //         $child = new CheckoutAssignedUser;
                //         $child->checkout_asset_user_id = $model->id;
                //         $child->user_id = $user_id;
                //         $child->save();
                //     }
                // }

                $asset = Asset::where(['id' => $assetId])->first();
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
            }

            if ($request->user_id <> null) {
                // foreach($request->user_id as $user_id){
                $a_log = new Actionlog;
                $a_log->user_id = Auth::user()->id;
                $a_log->action_type = 'checkout';
                $a_log->target_id = $request->user_id;
                $a_log->target_type = 'App\Models\User';
                $a_log->note = $request->note;
                $a_log->item_type = 'App\Models\Asset';
                $a_log->item_id = $assetId;
                $a_log->expected_checkin = $request->expected_checkin;
                $a_log->action_date = date('Y-m-d H:m:i');
                $a_log->save();
                // }
            }

            return redirect()->to('hardware')->with('success', trans('admin/hardware/message.checkout.success'));
        }
    }

    /**
     * Validate and process the form data to check out an asset to a user.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param AssetCheckoutRequest $request
     * @param int $assetId
     * @return Redirect
     * @since [v1.0]
     */
    public function store(AssetCheckoutRequest $request, $assetId)
    {
        try {
            // Check if the asset exists
            if (!$asset = Asset::find($assetId)) {
                return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.does_not_exist'));
            } elseif (!$asset->availableForCheckout()) {
                return redirect()->route('hardware.index')->with('error', trans('admin/hardware/message.checkout.not_available'));
            }
            $this->authorize('checkout', $asset);
            $admin = Auth::user();

            $target = $this->determineCheckoutTarget($asset);

            $asset = $this->updateAssetLocation($asset, $target);

            $checkout_at = date('Y-m-d H:i:s');
            if (($request->filled('checkout_at')) && ($request->get('checkout_at') != date('Y-m-d'))) {
                $checkout_at = $request->get('checkout_at');
            }

            $expected_checkin = '';
            if ($request->filled('expected_checkin')) {
                $expected_checkin = $request->get('expected_checkin');
            }

            if ($request->filled('status_id')) {
                $asset->status_id = $request->get('status_id');
            }

            if (!empty($asset->licenseseats->all())) {
                if (request('checkout_to_type') == 'user') {
                    foreach ($asset->licenseseats as $seat) {
                        $seat->assigned_to = $target->id;
                        $seat->save();
                    }
                }
            }

            if ($asset->checkOut($target, $admin, $checkout_at, $expected_checkin, e($request->get('note')), $request->get('name'))) {
                return redirect()->route('hardware.index')->with('success', trans('admin/hardware/message.checkout.success'));
            }

            // Redirect to the asset management page with error
            return redirect()->to("hardware/$assetId/checkout")->with('error', trans('admin/hardware/message.checkout.error') . $asset->getErrors());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', trans('admin/hardware/message.checkout.error'))->withErrors($asset->getErrors());
        } catch (CheckoutNotAllowed $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
