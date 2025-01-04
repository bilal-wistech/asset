<?php

namespace App\Presenters;

/**
 * Class CategoryPresenter
 */
class  RepairOptionsPresenter extends Presenter
{
    /**
     * Json Column Layout for bootstrap table
     * @return string
     */
    public static function dataTableLayout()
    {
        $layout = [
            [
                'field' => 'name',
                'searchable' => true,
                'sortable' => true,
                'title' => "Options",
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
		        'formatter' => 'tsrepairoptionsActionsFormatter',
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
