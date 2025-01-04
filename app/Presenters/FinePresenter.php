<?php

namespace App\Presenters;

/**
 * Class CategoryPresenter
 */
class FinePresenter extends Presenter
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
                'field' => 'fine_number',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Fine Number',
                'visible' => true,

            ],
            [
                'field' => 'asset_name',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('admin/hardware/form.name'),
                'visible' => true,
                'formatter' => 'hardwareLinkFormatter',
            ],
            [
                'field' => 'username',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.username'),
                'visible' => true,

            ],

            [
                'field' => 'fine_type',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.title'),
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
                'field' => 'fine_date',
                'searchable' => true,
                'sortable' => true,
                'visible' => true,
                'title' => trans('general.fine_date'),
                'formatter' => 'dateDisplayFormatter',
            ],
            [
                'field' => 'recieved',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => 'Recieved by user',

            ],

            [
                'field' => 'created_at',
                'searchable' => true,
                'sortable' => true,
                'visible' => true,
                'title' => trans('general.created_at'),
                'formatter' => 'dateDisplayFormatter',
            ],
            [
                'field' => 'action',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => trans('table.actions'),
                'formatter' => 'fineActionsFormatter',
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
