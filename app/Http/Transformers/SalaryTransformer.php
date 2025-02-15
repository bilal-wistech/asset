<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\Salary;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class SalaryTransformer
{
    public function transformSalary($salaries, $total)
    {
        $array = [];
        foreach ($salaries as $salary) {

            $array[] = self::transform($salary);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }
    public function transform($salary)
    {
        return [
            'driver' => optional($salary->driver)->username ?? 'Not Available',
            'base_salary' => optional($salary->driverSalary)->base_salary ?? 0,
            'total_amount_paid' => $salary->total_amount_paid ?? 0, // Sum of amount_paid
            'from_date' => $salary->from_date ?? '',
            'to_date' => $salary->to_date ?? '',
            'user_id' => optional($salary->user)->username ?? 'Not Available',
            'actions' => $this->getActionButtons($salary),
        ];
        return $array;
       
            
        
    }
    private function getActionButtons($salary)
    {
        $actions = '';

        
            $actions .= '<a href="' . route('salaries.edit', $salary) . '" 
                   class="btn btn-sm btn-info" title="Edit">
                   <i class="fa fa-pencil"></i>
                </a>';


        return $actions;
    }

}


