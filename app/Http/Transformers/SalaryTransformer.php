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

        //  dd($expData_value);

        if ($salary) {


            $array = [
                'id' => $salary->id,
                'riding_company' => $salary->ridingCompany != null ? $salary->ridingCompany->name : '',
                'driver' => $salary->driver && $salary->driver->username != null ? $salary->driver->username : 'not available',
                'amount_paid' => $salary->amount_paid,
                'from_date' => $salary->from_date ?? '',
                'to_date' => $salary->to_date ?? '',
                'created_by' => $salary->user->username ?? '',
                'created_at' => Helper::getFormattedDateObject($salary->created_at, 'datetime'),
                'actions' => $this->getActionButtons($salary)
            ];
            return $array;
        }
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
