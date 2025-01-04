<?php

namespace App\Presenters;

/**
 * Class CategoryPresenter
 */
class DailyEarningReportPresenter extends Presenter
{
    /**
     * Json Column Layout for bootstrap table
     * @return string
     */
    public static function dataTableLayout()
    {
        $layout = [
           

            [
                'field' => 'courier_id',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Courier ID',
                'visible' => true,
               
            ],
            [
                'field' => 'name',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Name',
                'visible' => true,
               
            ],
            [
                'field' => 'phone',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Phone',
                'visible' => true,
               
            ],
            [
                'field' => 'city',
                'searchable' => true,
                'sortable' => true,
                'title' => 'City',
                'visible' => true,
               
            ],
            [
                'field' => 'offline',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Offline',
                'visible' => true,
               
            ],
            [
                'field' => 'days_since_last_delivery',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Days Since Last delivery',
                'visible' => true,
               
            ],
            [
                'field' => 'days_since_last_offload',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Days Since Last Offered',
                'visible' => true,
               
            ],
            [
                'field' => 'earnings_without_tips_yesterday',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Earnings Without Tips Yesterday',
                'visible' => true,
               
            ],
            [
                'field' => 'hours_online_yesterday',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Hours Online Yesterday',
                'visible' => true,
               
            ],
            [
                'field' => 'hours_on_task_yesterday',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Hours on Task Yesterday',
                'visible' => true,
               
            ],
            [
                'field' => 'cash_balance',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Cash Balance',
                'visible' => true,
               
            ],
            [
                'field' => 'created_at',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Created At',
                'visible' => true,
               
            ],
             
           
        
        ];

        return json_encode($layout);
    }

    /**
     * Link to this categories name
     * @return string
     */
   

    /**
     * Url to view this item.
     * @return string
     */
   
}
