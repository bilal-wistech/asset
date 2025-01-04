<?php

namespace App\Presenters;

/**
 * Class CategoryPresenter
 */
class ExpencePresenter extends Presenter
{
    /**
     * Json Column Layout for bootstrap table
     * @return string
     */
    public static function dataTableLayout()
    {
        // Check the current route
        $isFuelExpenseRoute = request()->is('fuel_expense');

        $layout = [
            [
                'field' => 'image',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => 'Receipt',
                'visible' => true,
                'formatter' => 'downloadImageFormatter',
            ],
            [
                'field' => 'username',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.username'),
                'visible' => true,
            ],
            [
                'field' => 'type',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.title'),
                'visible' => true,
            ],
            [
                'field' => 'total_milage',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.total_milage'),
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
                'field' => 'created_at',
                'searchable' => true,
                'sortable' => true,
                'visible' => false,
                'title' => trans('general.created_at'),
                'formatter' => 'dateDisplayFormatter',
            ],
            [
                'field' => 'updated_at',
                'searchable' => true,
                'sortable' => true,
                'visible' => false,
                'title' => trans('general.updated_at'),
                'formatter' => 'dateDisplayFormatter',
            ],
        ];

        // Conditionally add approve and disapprove columns if not on the fuel_expense route
        if (!$isFuelExpenseRoute) {
            $layout[] = [
                'field' => 'approve',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => 'Approve',
            ];
            $layout[] = [
                'field' => 'disapprove',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => 'Disapprove',
            ];
        }

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
