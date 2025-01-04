<?php

namespace App\Http\Controllers;

use Str;
use View;
use Slack;
use TCPDF;
use Carbon\Carbon;
use App\Models\Asset;
use App\Helpers\Helper;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\Maintainance;
use Illuminate\Http\Request;
use App\Models\AssetMaintenance;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetMaintenancesExport;

/**
 * This controller handles all actions related to Asset Maintenance for
 * the Snipe-IT Asset Management application.
 *
 * @version    v2.0
 */
class AssetMaintenancesController extends Controller
{
    /**
     * Checks for permissions for this action.
     *
     * @todo This should be replaced with middleware and/or policies
     * @author  Vincent Sposato <vincent.sposato@gmail.com>
     * @version v1.0
     * @since [v1.8]
     * @return View
     */
    private static function getInsufficientPermissionsRedirect()
    {
        return redirect()->route('maintenances.index')
            ->with('error', trans('general.insufficient_permissions'));
    }

    /**
     *  Returns a view that invokes the ajax tables which actually contains
     * the content for the asset maintenances listing, which is generated in getDatatable.
     *
     * @todo This should be replaced with middleware and/or policies
     * @see AssetMaintenancesController::getDatatable() method that generates the JSON response
     * @author  Vincent Sposato <vincent.sposato@gmail.com>
     * @version v1.0
     * @since [v1.8]
     * @return View
     */
    public function index()
    {
        $this->authorize('view', Asset::class);
        return view('asset_maintenances/index');
    }

    /**
     *  Returns a form view to create a new asset maintenance.
     *
     * @see AssetMaintenancesController::postCreate() method that stores the data
     * @author  Vincent Sposato <vincent.sposato@gmail.com>
     * @version v1.0
     * @since [v1.8]
     * @return mixed
     */
    public function create()
    {
        $this->authorize('create', Asset::class);

        // Fetch all assets for the dropdown
        $assets = Asset::all();
        $assetMaintenanceType = Maintainance::pluck('name', 'id')->prepend('Select an asset maintenance type', '');
        $suppliers = Supplier::pluck('name', 'id');

        return view('asset_maintenances/edit', compact('suppliers'))
            ->with('assets', $assets) // Note: Passing the collection here
            ->with('assetMaintenanceType', $assetMaintenanceType)
            ->with('item', new AssetMaintenance);
    }

    /**
     *  Validates and stores the new asset maintenance
     *
     * @see AssetMaintenancesController::getCreate() method for the form
     * @author  Vincent Sposato <vincent.sposato@gmail.com>
     * @version v1.0
     * @since [v1.8]
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->authorize('update', Asset::class);
        // create a new model instance
        $assetMaintenance = new AssetMaintenance();
        $assetMaintenance->supplier_id = $request->input('supplier_id');
        $assetMaintenance->is_warranty = $request->input('is_warranty');
        $assetMaintenance->cost = Helper::ParseCurrency($request->input('cost'));
        $assetMaintenance->notes = $request->input('notes');
        $asset = Asset::find($request->input('asset_id'));

        if ((!Company::isCurrentUserHasAccess($asset)) && ($asset != null)) {
            return static::getInsufficientPermissionsRedirect();
        }

        // Save the asset maintenance data
        $assetMaintenance->asset_id = $request->input('asset_id');
        $maintenance = null;
        $assetMaintenanceType = $request->input('asset_maintenance_type');

        // Check if the value is numeric
        if (is_numeric($assetMaintenanceType)) {
            $maintenanceType = Maintainance::findOrFail($assetMaintenanceType);
            $maintenance = $maintenanceType->name;
        }
        // Save the asset maintenance data
        $assetMaintenance->asset_id = $request->input('asset_id');
        $assetMaintenance->asset_maintenance_type = is_numeric($assetMaintenanceType)
            ? $maintenance
            : $request->input('asset_maintenance_type');
        $assetMaintenance->title = $request->input('title');
        $assetMaintenance->start_date = $request->input('start_date');
        $assetMaintenance->completion_date = $request->input('completion_date');
        $assetMaintenance->user_id = Auth::id();

        if (
            ($assetMaintenance->completion_date !== null)
            && ($assetMaintenance->start_date !== '')
            && ($assetMaintenance->start_date !== '0000-00-00')
        ) {
            $startDate = Carbon::parse($assetMaintenance->start_date);
            $completionDate = Carbon::parse($assetMaintenance->completion_date);
            $assetMaintenance->asset_maintenance_time = $completionDate->diffInDays($startDate);
        }

        // Was the asset maintenance created?
        if ($assetMaintenance->save()) {
            // Redirect to the new asset maintenance page
            return redirect()->route('maintenances.index')
                ->with('success', trans('admin/asset_maintenances/message.create.success'));
        }

        return redirect()->back()->withInput()->withErrors($assetMaintenance->getErrors());
    }

    /**
     *  Returns a form view to edit a selected asset maintenance.
     *
     * @see AssetMaintenancesController::postEdit() method that stores the data
     * @author  Vincent Sposato <vincent.sposato@gmail.com>
     * @param int $assetMaintenanceId
     * @version v1.0
     * @since [v1.8]
     * @return mixed
     */
    public function edit($assetMaintenanceId = null)
    {
        $this->authorize('update', Asset::class);

        // Fetch the asset maintenance record
        $assetMaintenance = AssetMaintenance::findOrFail($assetMaintenanceId);

        if (!$assetMaintenance->asset) {
            return redirect()->route('maintenances.index')
                ->with('error', 'The asset associated with this maintenance does not exist.');
        }

        if (!Company::isCurrentUserHasAccess($assetMaintenance->asset)) {
            return static::getInsufficientPermissionsRedirect();
        }

        // Format dates and cost
        $assetMaintenance->completion_date = $assetMaintenance->completion_date == '0000-00-00' ? null : $assetMaintenance->completion_date;
        $assetMaintenance->start_date = $assetMaintenance->start_date == '0000-00-00' ? null : $assetMaintenance->start_date;
        $assetMaintenance->cost = $assetMaintenance->cost == '0.00' ? null : $assetMaintenance->cost;

        // Fetch all assets and preselect the associated asset
        $assets = Asset::all();
        $assetMaintenanceType = ['' => 'Select an improvement type'] + Maintainance::pluck('name', 'id')->toArray();
        $suppliers = Supplier::pluck('name', 'id');

        return view('asset_maintenances/edit', compact('suppliers'))
            ->with('assets', $assets)
            ->with('selectedAsset', $assetMaintenance->asset) // Preselect the related asset
            ->with('assetMaintenanceType', $assetMaintenanceType)
            ->with('item', $assetMaintenance);
    }


    /**
     *  Validates and stores an update to an asset maintenance
     *
     * @see AssetMaintenancesController::postEdit() method that stores the data
     * @author  Vincent Sposato <vincent.sposato@gmail.com>
     * @param Request $request
     * @param int $assetMaintenanceId
     * @return mixed
     * @version v1.0
     * @since [v1.8]
     */
    public function update(Request $request, $assetMaintenanceId = null)
    {
        $this->authorize('update', Asset::class);
        // Check if the asset maintenance exists
        if (is_null($assetMaintenance = AssetMaintenance::find($assetMaintenanceId))) {
            // Redirect to the asset maintenance management page
            return redirect()->route('maintenances.index')
                ->with('error', trans('admin/asset_maintenances/message.not_found'));
        } elseif (!Company::isCurrentUserHasAccess($assetMaintenance->asset)) {
            return static::getInsufficientPermissionsRedirect();
        }

        $assetMaintenance->supplier_id = $request->input('supplier_id');
        $assetMaintenance->is_warranty = $request->input('is_warranty');
        $assetMaintenance->cost = Helper::ParseCurrency($request->input('cost'));
        $assetMaintenance->notes = $request->input('notes');

        $asset = Asset::find(request('asset_id'));

        if (!Company::isCurrentUserHasAccess($asset)) {
            return static::getInsufficientPermissionsRedirect();
        }
        // dd($request->input('asset_maintenance_type'),Maintainance::findOrFail($request->input('asset_maintenance_type')));
        $maintenance = null;
        $assetMaintenanceType = $request->input('asset_maintenance_type');

        // Check if the value is numeric
        if (is_numeric($assetMaintenanceType)) {
            $maintenanceType = Maintainance::findOrFail($assetMaintenanceType);
            $maintenance = $maintenanceType->name;
        }
        // Save the asset maintenance data
        $assetMaintenance->asset_id = $request->input('asset_id');
        $assetMaintenance->asset_maintenance_type = is_numeric($assetMaintenanceType)
            ? $maintenance
            : $request->input('asset_maintenance_type');
        $assetMaintenance->title = $request->input('title');
        $assetMaintenance->start_date = $request->input('start_date');
        $assetMaintenance->completion_date = $request->input('completion_date');

        if (($assetMaintenance->completion_date == null)) {
            if (
                ($assetMaintenance->asset_maintenance_time !== 0)
                || (!is_null($assetMaintenance->asset_maintenance_time))
            ) {
                $assetMaintenance->asset_maintenance_time = null;
            }
        }

        if (
            ($assetMaintenance->completion_date !== null)
            && ($assetMaintenance->start_date !== '')
            && ($assetMaintenance->start_date !== '0000-00-00')
        ) {
            $startDate = Carbon::parse($assetMaintenance->start_date);
            $completionDate = Carbon::parse($assetMaintenance->completion_date);
            $assetMaintenance->asset_maintenance_time = $completionDate->diffInDays($startDate);
        }

        // Was the asset maintenance created?
        if ($assetMaintenance->save()) {

            // Redirect to the new asset maintenance page
            return redirect()->route('maintenances.index')
                ->with('success', trans('admin/asset_maintenances/message.edit.success'));
        }

        return redirect()->back()->withInput()->withErrors($assetMaintenance->getErrors());
    }

    /**
     *  Delete an asset maintenance
     *
     * @author  Vincent Sposato <vincent.sposato@gmail.com>
     * @param int $assetMaintenanceId
     * @version v1.0
     * @since [v1.8]
     * @return mixed
     */
    public function destroy($assetMaintenanceId)
    {
        $this->authorize('update', Asset::class);
        // Check if the asset maintenance exists
        if (is_null($assetMaintenance = AssetMaintenance::find($assetMaintenanceId))) {
            // Redirect to the asset maintenance management page
            return redirect()->route('maintenances.index')
                ->with('error', trans('admin/asset_maintenances/message.not_found'));
        } elseif (!Company::isCurrentUserHasAccess($assetMaintenance->asset)) {
            return static::getInsufficientPermissionsRedirect();
        }

        // Delete the asset maintenance
        $assetMaintenance->delete();

        // Redirect to the asset_maintenance management page
        return redirect()->route('maintenances.index')
            ->with('success', trans('admin/asset_maintenances/message.delete.success'));
    }

    /**
     *  View an asset maintenance
     *
     * @author  Vincent Sposato <vincent.sposato@gmail.com>
     * @param int $assetMaintenanceId
     * @version v1.0
     * @since [v1.8]
     * @return View
     */
    public function show($assetMaintenanceId)
    {
        $this->authorize('view', Asset::class);

        // Check if the asset maintenance exists
        if (is_null($assetMaintenance = AssetMaintenance::find($assetMaintenanceId))) {
            // Redirect to the asset maintenance management page
            return redirect()->route('maintenances.index')
                ->with('error', trans('admin/asset_maintenances/message.not_found'));
        } elseif (!Company::isCurrentUserHasAccess($assetMaintenance->asset)) {
            return static::getInsufficientPermissionsRedirect();
        }

        return view('asset_maintenances/view')->with('assetMaintenance', $assetMaintenance);
    }
    public function export(Request $request)
    {
        $query = AssetMaintenance::select('asset_maintenances.*')->with('asset', 'asset.model', 'asset.location', 'supplier', 'asset.company', 'admin');

        // Apply filters based on request parameters
        if ($request->filled('search')) {
            $query->TextSearch($request->input('search'));
        }

        if ($request->filled('asset_id')) {
            $query->where('asset_id', '=', $request->input('asset_id'));
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', '=', $request->input('supplier_id'));
        }

        if ($request->filled('asset_maintenance_type')) {
            $query->where('asset_maintenance_type', '=', $request->input('asset_maintenance_type'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('completion_date')) {
            $query->whereDate('completion_date', '<=', $request->input('completion_date'));
        }
        $maintenances = $query->get();

        // Generate and return Excel download response
        return Excel::download(new AssetMaintenancesExport($maintenances), 'asset_maintenance_report.xlsx');
    }
}
