<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\AssetAssignment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class AssetAssignmentTransformer
{
    public function transformData(Collection $models, $total)
    {
        $array = [];
        foreach ($models as $model) {
            $array[] = self::transformRecords($model);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformRecords(AssetAssignment $model = null)
    {

        // We only ever use item_count for categories in this transformer, so it makes sense to keep it
        // simple and do this switch here.


        if ($model) {
            $array = [
                'id'             => (int) $model->id,
                'asset_id'       => $model->asset->name,
                'assigned_at'    => Helper::getFormattedDateObject($model->assigned_at, 'datetime'),
                'assigned_by'    => $model->assignedBy->show_name,
                'assigned_users' => $this->customAssignedUsers($model),
                
                'created_at'     => Helper::getFormattedDateObject($model->created_at, 'datetime'),
                'updated_at'     => Helper::getFormattedDateObject($model->updated_at, 'datetime'),
                'created_by'     => $model->created_by,
                'updated_by'     => $model->updated_by,
            ];

            $permissions_array['available_actions'] = [
                'update' => Gate::allows('update', AssetAssignment::class),
                'delete' => $model->isDeletable(),
            ];
            $array += $permissions_array;

            return $array;
        }
    }


    public function customAssignedUsers($model)
    {
        $assigned_to='';
        if(isset($model->userIds) && $model->userIds<>null){
            foreach($model->userIds as $key => $user){
                // dd($user->userInfo);
               $assigned_to .= '<a href="'.url('users/'.$user->userInfo->id).'" class="label label-primary" style="margin-right: 3px;">'.$user->userInfo->show_name.'</a>';
            }
        }

        // dd($assigned_to);
        return $assigned_to;
    }
}
