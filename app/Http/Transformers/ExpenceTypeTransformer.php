<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\AddExpence;
use App\Models\TypeOfExpence;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class ExpenceTypeTransformer
{
    public function transformtype(Collection $expData)
    {
        $array = [];
        foreach ($expData as $expData_value) {
            
            $array[] = self::transform($expData_value);
        }

        return (new DatatablesTransformer)->transformDatatables($array);
    }
    public function transform( $expData_value)
    {

        //  dd($expData_value);

        if ($expData_value) {
           
        
            $array = [
                'id' =>$expData_value->id,  
                'type' =>$expData_value->title,              
            
                'created_at' => Helper::getFormattedDateObject($expData_value->created_at, 'datetime'),
                        
               
               
            ];
            
            $permissions_array['available_actions'] = [
                'update' => Gate::allows('update' , TypeOfExpence::class),
                'delete' => Gate::allows('delete' , TypeOfExpence::class),
            ];

             $array += $permissions_array;
     
            
             return $array;
        }
    }

    
}
