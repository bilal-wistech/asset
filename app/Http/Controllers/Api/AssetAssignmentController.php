<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Transformers\AssetAssignmentTransformer;
use App\Models\AssetAssignment;
use Illuminate\Http\Request;
use App\Http\Requests\ImageUploadRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetAssignmentController extends Controller
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
        $this->authorize('view', AssetAssignment::class);
        $allowed_columns = [
            'id',
            'asset_id',
            'assigned_at',
            'assigned_by',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ];

        $model = AssetAssignment::select([
            'id',
            'asset_id',
            'assigned_at',
            'assigned_by',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            ]);


        /*
         * This checks to see if we should override the Admin Setting to show archived assets in list.
         * We don't currently use it within the Snipe-IT GUI, but will be useful for API integrations where they
         * may actually need to fetch assets that are archived.
         *
         * @see \App\Models\Category::showableAssets()
         */
        // if ($request->input('archived')=='true') {
        //     $model = $model->withCount('assets as assets_count');
        // } else {
        //     $model = $model->withCount('showableAssets as assets_count');
        // }

        if ($request->filled('search')) {
            $model = $model->TextSearch($request->input('search'));
        }

        // if ($request->filled('name')) {
        //     $model->where('name', '=', $request->input('name'));
        // }

        // if ($request->filled('category_type')) {
        //     $model->where('category_type', '=', $request->input('category_type'));
        // }

        // if ($request->filled('use_default_eula')) {
        //     $model->where('use_default_eula', '=', $request->input('use_default_eula'));
        // }

        // if ($request->filled('require_acceptance')) {
        //     $model->where('require_acceptance', '=', $request->input('require_acceptance'));
        // }

        // if ($request->filled('checkin_email')) {
        //     $model->where('checkin_email', '=', $request->input('checkin_email'));
        // }

        // Make sure the offset and limit are actually integers and do not exceed system limits
        $offset = ($request->input('offset') > $model->count()) ? $model->count() : abs($request->input('offset'));
        // $limit = app('api_limit_value');
        $limit = config('app.max_results');

        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? $request->input('sort') : 'asset_id';
        $model->orderBy($sort, $order);

        $total = $model->count();
        $model = $model->skip($offset)->take($limit)->get();

        // dd($model);

        return (new AssetAssignmentTransformer)->transformData($model, $total);

    }


}
