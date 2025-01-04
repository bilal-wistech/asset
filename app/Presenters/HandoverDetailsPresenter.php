<?php

namespace App\Presenters;

/**
 * Class CategoryPresenter
 */
class HandoverDetailsPresenter extends Presenter
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
                'title' => 'ID',
                'visible' => false,

            ],
            [
                'field' => 'asset_name',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Asset Name',
                'visible' => false,

            ],
            [
                'field' => 'asset_tag',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Asset Tag',
                'visible' => true,

            ],
            [
                'field' => 'image',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => 'Image',
                'visible' => true,
                'formatter' => 'downloadImageFormatter',
            ],

            [
                'field' => 'checkin_date',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Check In Date',
                'visible' => true,

            ],
            [
                'field' => 'user',
                'searchable' => true,
                'sortable' => true,
                'title' => 'User',
                'visible' => true,

            ],
            [
                'field' => 'reason',
                'searchable' => false,
                'sortable' => false,
                'title' => 'Reason',
                'visible' => true,

            ],
            [
                'field' => 'notes',
                'searchable' => false,
                'sortable' => false,
                'title' => 'Notes',
                'visible' => true,

            ],
            [
                'field' => 'created_at',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Created At',
                'visible' => false,
                'formatter' => 'dateDisplayFormatter'
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
