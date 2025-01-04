<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyEarningReportsExport implements FromCollection, WithMapping, WithHeadings
{
    protected $dailyEarningReports;

    public function __construct($dailyEarningReports)
    {
        $this->dailyEarningReports = $dailyEarningReports;
    }

    public function collection()
    {
        return $this->dailyEarningReports;
    }
    public function map($dailyEarningReport): array
    {
        return [
            $dailyEarningReport->courier_id ?? '',
            $dailyEarningReport->name ?? '',
            $dailyEarningReport->phone ?? '',
            $dailyEarningReport->city ?? '',
            $dailyEarningReport->offline ?? '',
            $dailyEarningReport->days_since_last_delivery ?? '',
            $dailyEarningReport->days_since_last_offload ?? '',
            $dailyEarningReport->earnings_without_tips_yesterday ?? '',
            $dailyEarningReport->hours_online_yesterday ?? '',
            $dailyEarningReport->hours_on_task_yesterday ?? '',
            $dailyEarningReport->cash_balance ?? '',
            $dailyEarningReport->created_at ?? ''
        ];
    }

    public function headings(): array
    {
        return [
            'Courier ID',
            'Name',
            'Phone',
            'City',
            'Offline',
            'Days Since Last Delivery',
            'Days Since Last Offered',
            'Earnings Without Tips Yesterday',
            'Hours Online Yesterday',
            'Hours On Task Yesterday',
            'Cash Balance',
            'Created At',
        ];
    }
}
