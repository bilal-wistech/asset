<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Transformers\RepairOptionsTransformer;

use App\Models\tsrepairoptions;
use App\Models\User;
use Illuminate\Http\Request;


class RepairOptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  \App\Http\Requests\ImageUploadRequest $request
     * @return \Illuminate\Http\Response
     */


    public function ShowRepairOptions()
    {
      $options = tsrepairoptions::all();
      return (new RepairOptionsTransformer)->transformoptions($options );
    }

    public function index()
    {
        $repairoptions_data = tsrepairoptions::all();
        return response()->json($repairoptions_data);
    }
}
