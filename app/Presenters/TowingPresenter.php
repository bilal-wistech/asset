<?php

namespace App\Presenters;

/**
 * Class CategoryPresenter
 */
class TowingPresenter extends Presenter
{
    /**
     * Json Column Layout for bootstrap table
     * @return string
     */
    public static function dataTableLayout()
    {
        $layout = [

            [
                'field' => 'towing_date',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => 'Date',
                'visible' => true,
                

            ],
            [
                'field' => 'username',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.username'),
                'visible' => true,

            ],


            [
                'field' => 'asset_name',
                'searchable' => true,
                'sortable' => true,
                'title' => 'Assets',
                'visible' => true,

            ],

            [
                'field' => 'location',
                'searchable' => true,
                'sortable' => true,
                'title' =>'Location',
                'visible' => true,
            ], [
                'field' => 'reason',
                'searchable' => false,
                'sortable' => true,
                'title' => 'Reasons',
                'visible' => true,
            ],
            
            [
                'field' => 'created_at',
                'searchable' => true,
                'sortable' => true,
                'visible' => false,
                'title' => trans('general.created_at'),
                'formatter' => 'dateDisplayFormatter',
            ], [
                'field' => 'updated_at',
                'searchable' => true,
                'sortable' => true,
                'visible' => false,
                'title' => trans('general.updated_at'),
                'formatter' => 'dateDisplayFormatter',
            ],


            [
                'field' => 'failed_reason',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => 'Failed Reasons',

            ],
            [
                'field' => 'approve',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => 'Approve',

            ],
            // [
            //     'field' => 'disapprove',
            //     'searchable' => false,
            //     'sortable' => false,
            //     'switchable' => false,
            //     'title' => 'Disapprove',

            // ],
            //     [
            //         'field' => 'disapprove',
            //         'searchable' => false,
            //         'sortable' => false,
            //         'switchable' => false,
            //         'title' => 'Disapprove',
            // 'formatter' => '<a href="" class="btn btn-sm bg-red" data-tooltip="true" value="disapprove" id="approve">Disapprove</a>',
            //     ],
        ];

        return json_encode($layout);
    }

    /**
     * Link to this categories name
     * @return string
     */
    public function nameUrl()
    {
        return (string) link_to_route('grid.show', $this->name, $this->id);
    }

    /**
     * Url to view this item.
     * @return string
     */
    public function viewUrl()
    {
        return route('grid.show', $this->id);
    }
}
