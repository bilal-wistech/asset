<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset;
use App\Helpers\Helper;
use App\Models\Statuslabel;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Transformers\AssetsTransformer;
use App\Http\Transformers\PieChartTransformer;
use App\Http\Transformers\StatuslabelsTransformer;

class StatuslabelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', Statuslabel::class);
        $allowed_columns = ['id', 'name', 'created_at', 'assets_count', 'color', 'notes', 'default_label'];

        $statuslabels = Statuslabel::withCount('assets as assets_count');

        if ($request->filled('search')) {
            $statuslabels = $statuslabels->TextSearch($request->input('search'));
        }

        if ($request->filled('name')) {
            $statuslabels->where('name', '=', $request->input('name'));
        }


        // if a status_type is passed, filter by that
        if ($request->filled('status_type')) {
            if (strtolower($request->input('status_type')) == 'pending') {
                $statuslabels = $statuslabels->Pending();
            } elseif (strtolower($request->input('status_type')) == 'archived') {
                $statuslabels = $statuslabels->Archived();
            } elseif (strtolower($request->input('status_type')) == 'deployable') {
                $statuslabels = $statuslabels->Deployable();
            } elseif (strtolower($request->input('status_type')) == 'undeployable') {
                $statuslabels = $statuslabels->Undeployable();
            }
        }

        // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
        // case we override with the actual count, so we should return 0 items.
        $offset = (($statuslabels) && ($request->get('offset') > $statuslabels->count())) ? $statuslabels->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? $request->input('sort') : 'created_at';
        $statuslabels->orderBy($sort, $order);

        $total = $statuslabels->count();
        $statuslabels = $statuslabels->skip($offset)->take($limit)->get();

        return (new StatuslabelsTransformer)->transformStatuslabels($statuslabels, $total);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Statuslabel::class);
        $request->except('deployable', 'pending', 'archived');

        if (! $request->filled('type')) {
            return response()->json(Helper::formatStandardApiResponse('error', null, ['type' => ['Status label type is required.']]), 500);
        }

        $statuslabel = new Statuslabel;
        $statuslabel->fill($request->all());

        $statusType = Statuslabel::getStatuslabelTypesForDB($request->input('type'));
        $statuslabel->deployable = $statusType['deployable'];
        $statuslabel->pending = $statusType['pending'];
        $statuslabel->archived = $statusType['archived'];
        $statuslabel->color             =  $request->input('color');
        $statuslabel->show_in_nav       =  $request->input('show_in_nav', 0);
        $statuslabel->default_label     =  $request->input('default_label', 0);


        if ($statuslabel->save()) {
            return response()->json(Helper::formatStandardApiResponse('success', $statuslabel, trans('admin/statuslabels/message.create.success')));
        }
        return response()->json(Helper::formatStandardApiResponse('error', null, $statuslabel->getErrors()));

    }

    /**
     * Display the specified resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Statuslabel::class);
        $statuslabel = Statuslabel::findOrFail($id);

        return (new StatuslabelsTransformer)->transformStatuslabel($statuslabel);
    }


    /**
     * Update the specified resource in storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', Statuslabel::class);
        $statuslabel = Statuslabel::findOrFail($id);
        
        $request->except('deployable', 'pending', 'archived');


        if (! $request->filled('type')) {
            return response()->json(Helper::formatStandardApiResponse('error', null, 'Status label type is required.'));
        }

        $statuslabel->fill($request->all());

        $statusType = Statuslabel::getStatuslabelTypesForDB($request->input('type'));
        $statuslabel->deployable = $statusType['deployable'];
        $statuslabel->pending = $statusType['pending'];
        $statuslabel->archived = $statusType['archived'];
        $statuslabel->color             =  $request->input('color');
        $statuslabel->show_in_nav       =  $request->input('show_in_nav', 0);
        $statuslabel->default_label     =  $request->input('default_label', 0);

        if ($statuslabel->save()) {
            return response()->json(Helper::formatStandardApiResponse('success', $statuslabel, trans('admin/statuslabels/message.update.success')));
        }

        return response()->json(Helper::formatStandardApiResponse('error', null, $statuslabel->getErrors()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Statuslabel::class);
        $statuslabel = Statuslabel::findOrFail($id);
        $this->authorize('delete', $statuslabel);

        // Check that there are no assets associated
        if ($statuslabel->assets()->count() == 0) {
            $statuslabel->delete();

            return response()->json(Helper::formatStandardApiResponse('success', null, trans('admin/statuslabels/message.delete.success')));
        }

        return response()->json(Helper::formatStandardApiResponse('error', null, trans('admin/statuslabels/message.assoc_assets')));
    }



     /**
     * Show a count of assets by status label for pie chart
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return array
     */
    public function getAssetCountByStatuslabel(Request $request)
    {
        $total_deployed = $request->total_deployed;
       
        $this->authorize('view', Statuslabel::class);
        $statuslabels = Statuslabel::withCount('assets')->get();
        
    // dd(Statuslabel::get()->toArray());

        $total = Array();

        foreach ($statuslabels as $statuslabel) {
            $count = $statuslabel->assets_count;
            $label = $statuslabel->name;

            if(strtolower($label) == 'pending'){
                $count = Asset::Pending()->count();
            }
            if(strtolower($label) == 'archived'){
                $count = Asset::Archived()->count();
            }
            if(strtolower($label) == 'ready to deploy'){
                $count = Asset::RTD()->count();
            }

            // Pending
            $total[$label]['label'] = $label;
            $total[$label]['count'] = $count;


            if ($statuslabel->color != '') {
                $total[$statuslabel->name]['color'] = $statuslabel->color;
            }
        }

        $total["All Deployed"]['label'] = "All Deployed";
        $total["All Deployed"]['count'] = $total_deployed;
        $total["All Deployed"]['color'] = "black";

        return (new PieChartTransformer())->transformPieChartDate($total);

    }

    /**
     * Show a count of assets by meta status type for pie chart
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v6.0.11]
     * @return array
     */
    public function getAssetCountByMetaStatus()
    {
        $this->authorize('view', Statuslabel::class);

        $total['rtd']['label'] = trans('general.ready_to_deploy');
        $total['rtd']['count'] = Asset::RTD()->count();

        $total['deployed']['label'] = trans('general.deployed');
        $total['deployed']['count'] = Asset::Deployed()->count();

        $total['archived']['label'] = trans('general.archived');
        $total['archived']['count'] = Asset::Archived()->count();

        $total['pending']['label'] = trans('general.pending');
        $total['pending']['count'] = Asset::Pending()->count();

        $total['undeployable']['label'] = trans('general.undeployable');
        $total['undeployable']['count'] = Asset::Undeployable()->count();

        return (new PieChartTransformer())->transformPieChartDate($total);
    }

    /**
     * Display the specified resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assets(Request $request, $id)
    {
        $this->authorize('view', Statuslabel::class);
        $this->authorize('index', Asset::class);
        $assets = Asset::where('status_id', '=', $id)->with('assignedTo');

        $allowed_columns = [
            'id',
            'name',
        ];

        $offset = request('offset', 0);
        $limit = $request->input('limit', 50);
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? $request->input('sort') : 'created_at';
        $assets->orderBy($sort, $order);

        $total = $assets->count();
        $assets = $assets->skip($offset)->take($limit)->get();


        return (new AssetsTransformer)->transformAssets($assets, $total);
    }


    /**
     * Returns a boolean response based on whether the status label
     * is one that is deployable.
     *
     * This is used by the hardware create/edit view to determine whether
     * we should provide a dropdown of users for them to check the asset out to.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return bool
     */
    public function checkIfDeployable($id)
    {
        $statuslabel = Statuslabel::findOrFail($id);
        if ($statuslabel->getStatuslabelType() == 'deployable') {
            return '1';
        }

        return '0';
    }
}
