<?php

namespace App\Presenters;

/**
 * Class CategoryPresenter
 */
class DeductionPresenter extends Presenter
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
                'title' => trans('general.id'),
                'visible' => false,
            ],
            [
                'field' => 'username',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.username'),
                'visible' => true,
               
            ],
            [
                'field' => 'amount',
                'searchable' => false,
                'sortable' => true,
                'title' => trans('general.amount'),
                'visible' => true,
            ], 
            [
                'field' => 'deduction_date',
                'searchable' => true,
                'sortable' => true,
                'visible' => true,
                'title' =>'Deduction Date',
                'formatter' => 'dateDisplayFormatter',
            ],
            [
                'field' => 'reason',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Reason',
                'visible' => true,
               
            ],
            [
                'field' => 'note',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Description',
                'visible' => true,
                
            ],
            
            
            [
                'field' => 'created_at',
                'searchable' => true,
                'sortable' => true,
                'visible' => true,
                'title' => trans('general.created_at'),
                'formatter' => 'dateDisplayFormatter',
            ], [
                'field' => 'action',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => trans('table.actions'),
		      'formatter' => 'deductionActionsFormatter',
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
