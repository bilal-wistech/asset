<?php

namespace App\Presenters;

/**
 * Class InsurancePresenter
 */
class AssetAssignmentPresenter extends Presenter
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
                'field' => 'assigned_at',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.assigned_at'),
                'visible' => true,
                'formatter' => 'dateDisplayFormatter',
            ],
            [
                'field' => 'assigned_by',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.assigned_by'),
                'visible' => false,
            ],
            [
                'field' => 'assigned_users',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('general.assigned_users'),
                'visible' => false,
            ],
            
            [
                'field' => 'actions',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => trans('table.actions'),
		        'formatter' => 'assetassignmentActionsFormatter',
            ],
        ];

        
        // echo"<pre>"; print_r(json_encode($layout)); echo"</pre>"; 

        return json_encode($layout);
    }



    /**
     * Url to view this item.
     * @return string
     */
    public function viewUrl()
    {
        return route('asset-assignment.show', $this->id);
    }
}
