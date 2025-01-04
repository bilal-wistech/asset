<?php

namespace App\Presenters;

/**
 * Class InsurancePresenter
 */
class InsurancePresenter extends Presenter
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
                'field' => 'asset_id',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.asset_id'),
                'visible' => true,
            ], 
            [
                'field' => 'vendor_id',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.vendor_id'),
                'visible' => true,
            ],
            [
                'field' => 'towingsavailable',
                'searchable' => true,
                'sortable' => true,
                'title' => "Towings Available",
                'visible' => true,
            ],
            [
                'field' => 'insurance_date',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.insurance_date'),
                'visible' => false,
            ],
            [
                'field' => 'insurance_from',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.insurance_from'),
                'visible' => true,
            ],
            [
                'field' => 'insurance_to',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.insurance_to'),
                'visible' => true,
            ],
            [
                'field' => 'amount',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.amount'),
                'visible' => true,
            ],
            [
                'field' => 'premium_type',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.premium_type'),
                'visible' => true,
            ],
            [
                'field' => 'cost',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.cost'),
                'visible' => true,
            ],
            [
                'field' => 'no_of_drivers_allowed',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.no_of_drivers_allowed'),
                'visible' => false,
            ],
            [
                'field' => 'driver_cost',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.driver_cost'),
                'visible' => false,
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
                'field' => 'actions',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('table.actions'),
		        'formatter' => 'insuranceActionsFormatter',
                
            ],
        ];

        
        // echo"<pre>"; print_r(json_encode($layout)); echo"</pre>"; 

        return json_encode($layout); //some changes
    }



    /**
     * Url to view this item.
     * @return string
     */
    public function viewUrl()
    {
        return route('insurance.show', $this->id);
    }
}
