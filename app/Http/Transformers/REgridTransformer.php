<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\AddExpence;
use App\Models\TypeOfExpence;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class REgridTransformer
{
    public function transformgrid(Collection $expData, $total)
    {
        $array = [];
        foreach ($expData as $expData_value) {

            $array[] = self::transform($expData_value);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }
    public function transform($expData_value)
    {

        // dd($expData_value);

        if ($expData_value) {

            if ($expData_value->approved == 0) {
                $array = [
                    'image' => ($expData_value->image) ? url(e($expData_value->image)) : null,
                    'type' => $expData_value->type != null && $expData_value->type->title != null ? $expData_value->type->title : 'Type is not available',
                    'username' => $expData_value->userdata != null ? $expData_value->userdata->username : 'Username is not available',
                    'total_milage' => $expData_value->total_milage,
                    'amount' => $expData_value->amount,
                    'created_at' => Helper::getFormattedDateObject($expData_value->created_at, 'datetime'),
                    'updated_at' => Helper::getFormattedDateObject($expData_value->updated_at, 'datetime'),
                    'approve' =>  '<button class="btn btn-sm bg-blue approve-btn" data-id="' . $expData_value->id . '">Approve</button>',
                ];
            } else {
                $array = [
                    'image' => ($expData_value->image) ? url(e($expData_value->image)) : null,
                    'type' => $expData_value->type != null && $expData_value->type->title != null ? $expData_value->type->title : 'Type is not available',
                    'username' => $expData_value->userdata != null ? $expData_value->userdata->username : 'Username is not available',
                    'total_milage' => $expData_value->total_milage,
                    'amount' => $expData_value->amount,
                    'created_at' => Helper::getFormattedDateObject($expData_value->created_at, 'datetime'),
                    'updated_at' => Helper::getFormattedDateObject($expData_value->updated_at, 'datetime'),
                    'disapprove' =>  '<button class="btn btn-sm bg-red disapprove-btn" data-id="' . $expData_value->id . '">Disapprove</button>',
                ];
            }

            // $permissions_array['available_actions'] = [
            //     'update' => Gate::allows('update', Category::class),
            //     'delete' => $category->isDeletable(),
            // ];

            // $array += $permissions_array;

            return $array;
        }
    }
}
