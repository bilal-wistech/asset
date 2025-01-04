<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\tsrepairoptions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class RepairOptionsTransformer
{
    public function transformoptions(Collection $optionData)
    {
        //dd($optionData);
        $array = [];
        foreach ($optionData as $optionData) {
            $array[] = self::transform($optionData);
        }
        return (new DatatablesTransformer)->transformDatatables($array);
    }

    public function transform( $optionData)
    {

        //  dd($optionData);
        if ($optionData) {
            $array = [
                'id' =>$optionData->id,  
                'name' =>$optionData->name,              
                'created_at' => Helper::getFormattedDateObject($optionData->created_at, 'datetime'),
            ];
            // dd($optionData);
            $permissions_array['available_actions'] = [
                'update' => Gate::allows('update' , tsrepairoptions::class),
                'delete' => Gate::allows('delete' , tsrepairoptions::class),
            ];
             $array += $permissions_array;
             return $array;
        }
    }
}
