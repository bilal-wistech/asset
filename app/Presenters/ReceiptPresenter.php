<?php

namespace App\Presenters;

/**
 * Class CategoryPresenter
 */
class ReceiptPresenter extends Presenter
{
    /**
     * Json Column Layout for bootstrap table
     * @return string
     */
    public static function dataTableLayout()
    {
        $layout = [
            [
                'field' => 'id',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => 'Adjustment ID',
                'visible' => true,
                'formatter' => 'receiptLinkFormatter',
            ],
            [
                'field' => 'username',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.username'),
                'visible' => true,
               
            ],
            [
                'field' => 'deduction_way',
                'searchable' => false,
                'sortable' => true,
                'title' => 'Adjustment Method',
                'visible' => true,
            ], 
            [
                'field' => 'date',
                'searchable' => true,
                'sortable' => true,
                'visible' => true,
                'title' =>'Adjustment Date',
                'formatter' => 'dateDisplayFormatter',
            ],          
            [
                'field' => 'total_amount',
                'searchable' => true,
                'sortable' => true,
                'visible' => true,
                'title' => 'Amount Paid',
            ], 
            [
                'field' => 'action',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => trans('table.actions'),
                'formatter' => 'receiptActionsFormatter',
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
