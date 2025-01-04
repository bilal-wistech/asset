<?php

namespace App\Presenters;

/**
 * Class CategoryPresenter
 */
class ExpenceTypePresenter extends Presenter
{
    /**
     * Json Column Layout for bootstrap table
     * @return string
     */
    public static function dataTableLayout()
    {
        $layout = [
           

            [
                'field' => 'type',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.title'),
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
		      'formatter' => 'expensetypeActionsFormatter',
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
