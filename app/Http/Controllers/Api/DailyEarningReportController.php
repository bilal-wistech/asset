<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\DailyEarningReportTransformer;
use App\Models\DailyEarningReport;
use Illuminate\Http\Request;

class DailyEarningReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', DailyEarningReport::class);

        $dailyEarningReports = DailyEarningReport::select('daily_earning_reports.*');

        if ($request->filled('search')) {
            $dailyEarningReports = $dailyEarningReports->TextSearch($request->input('search'));
        }
        if ($request->filled('courier_id')) {
            $dailyEarningReports->where('courier_id', '=', $request->input('courier_id'));
        }
        if ($request->filled('start_date')) {
            $dailyEarningReports->whereDate('created_at', '>=', $request->input('start_date'));
        }
    
        if ($request->filled('end_date')) {
            $dailyEarningReports->whereDate('created_at', '<=', $request->input('end_date'));
        }
        // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
        // case we override with the actual count, so we should return 0 items.
        $offset = (($dailyEarningReports) && ($request->get('offset') > $dailyEarningReports->count())) ? $dailyEarningReports->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $allowed_columns = [
            'id',
            'courier_id',
            'user_id',
            'name',
            'phone',
            'city',
            'offline',
            'days_since_last_delivery',
            'days_since_last_offload',
            'earnings_without_tips_yesterday',
            'hours_online_yesterday',
            'hours_on_task_yesterday',
            'cash_balance',
            'created_at'
        ];
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
        $dailyEarningReports = $dailyEarningReports->orderBy($sort, $order);

        $total = $dailyEarningReports->count();
        $dailyEarningReports = $dailyEarningReports->skip($offset)->take($limit)->get();
        return (new DailyEarningReportTransformer())->transformDailyEarningReport($dailyEarningReports, $total);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
