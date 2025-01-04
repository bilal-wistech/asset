<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\DailyEarningReport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

class DailyEarningReportTransformer
{
    public function transformDailyEarningReport(Collection $dailyEarningReports, $total)
    {
        $array = [];
        foreach ($dailyEarningReports as $dailyEarningReport) {
            $array[] = self::transformDailyEarning($dailyEarningReport);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformDailyEarning(DailyEarningReport $dailyEarningReport)
    {
        $array = [
            'courier_id'         => ($dailyEarningReport->courier_id) ? ($dailyEarningReport->courier_id) : null,
            'name'      => ($dailyEarningReport->name) ? ($dailyEarningReport->name) : null,
            'phone'          => ($dailyEarningReport->phone) ? ($dailyEarningReport->phone) : null,
            'city'          => ($dailyEarningReport->city) ? ($dailyEarningReport->city) : null,
            'offline'         => ($dailyEarningReport->offline),
            'days_since_last_delivery'          => ($dailyEarningReport->days_since_last_delivery) ? ($dailyEarningReport->days_since_last_delivery) : '',
            'days_since_last_offload'     => ($dailyEarningReport->days_since_last_offload) ? ($dailyEarningReport->days_since_last_offload) : '',
            'earnings_without_tips_yesterday'    => ($dailyEarningReport->earnings_without_tips_yesterday) ? ($dailyEarningReport->earnings_without_tips_yesterday) : '',
            'hours_online_yesterday'    => ($dailyEarningReport->hours_online_yesterday) ? ($dailyEarningReport->hours_online_yesterday) : '',
            'hours_on_task_yesterday'    => ($dailyEarningReport->hours_on_task_yesterday) ? ($dailyEarningReport->hours_on_task_yesterday) : '',
            'cash_balance'    => ($dailyEarningReport->cash_balance) ? ($dailyEarningReport->cash_balance) : '',
            'created_at' => Helper::convertDateTimeFormat($dailyEarningReport->created_at, 'timestamp'),
            'updated_at' => Helper::convertDateTimeFormat($dailyEarningReport->updated_at, 'timestamp'),

        ];
        return $array;
    }
}
