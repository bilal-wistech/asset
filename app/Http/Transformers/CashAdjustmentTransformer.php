<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\CashAdjustment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class SalaryTransformer
{
    public function transformSalary($cashAdjustments, $total)
    {
        $array = [];
        foreach ($cashAdjustments as $cashAdjustment) {

            $array[] = self::transform($cashAdjustment);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }
    public function transform($cashAdjustment)
    {
        return [
            'driver' => optional($cashAdjustment->driver)->username ?? 'Not Available',
            'user_id' => optional($cashAdjustment->user)->username ?? 'Not Available',
            'amount' => $cashAdjustment->amount ?? 0, 
            'created_at' => $cashAdjustment->created_at ?? '',
            'updated_at' => $cashAdjustment->updated_at ?? '',
            'actions' => $this->getActionButtons($cashAdjustment),
        ];
        return $array;
       
            
        
    }
    private function getActionButtons($cashAdjustment)
    {
        $actions = '';

      
            $actions .= '<a href="' . route('cash-adjustments.edit', $cashAdjustment) . '" 
                   class="btn btn-sm btn-info" title="Edit">
                   <i class="fa fa-pencil"></i>
                </a>';

                // Delete Button
            $actions .= '<form action="' . route('cash-adjustments.destroy', $cashAdjustment) . '" method="POST" style="display:inline;">
            ' . csrf_field() . '
            ' . method_field("DELETE") . '
            <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Are you sure you want to delete this?\')">
                <i class="fa fa-trash"></i>
            </button>
            </form>';


        return $actions;
    }

}


