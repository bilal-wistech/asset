<?php

namespace App\Http\Transformers;

use App\Models\Receipt;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

class SalaryCashTransformer
{
    /**
     * Transform a collection of receipts.
     *
     * @param Collection $receipts
     * @param int $total
     * @return array
     */
    public function transformSalaryCash($salaryCash, $total)
    {
        $array = [];
        foreach ($salaryCash as $cash) {
            //dd($receipt->receipt_id);
            $array[] = self::transform($cash);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    /**
     * Transform a single deduction.
     *
     * @param \App\Models\Deduction $receipt
     * @return array
     */
    public function transform($cash)
    {
        
            $array = [
                'id' => 'SC-ADJ-' . $cash->receipt_id,
                'username' => ($cash->driver ? $cash->driver->first_name . ' ' . $cash->driver->last_name : 'Unknown') . ' (' . $cash->driver->username . ')',
                'deduction_way' => $cash->deduction_way,
                'date' => $cash->date,
                'created_at' => $cash->created_at ? Helper::getFormattedDateObject($cash->created_at, 'datetime') : null,
                'total_amount' => round($cash->total_amount, 2),
                'salary_to_be_included_from_to' => $cash->salary_to_be_included_from.' - '.$cash->salary_to_be_included_to,
                'added_by' => ($cash->user ? $cash->user->first_name . ' ' . $cash->user->last_name : 'Unknown') . ' (' . $cash->user->username . ')',
                // 'actions' => $this->getActionButtons($cash),
            ];

            return $array;
    }
    private function getActionButtons($salary)
    {
        $actions = '';

      
            // $actions .= '<a href="' . route('salaries.edit', $salary) . '" 
            //        class="btn btn-sm btn-info" title="Edit">
            //        <i class="fa fa-pencil"></i>
            //     </a>';

            //      // View Button
            // $actions .= '<a href="' . route('salaries.show', $salary) . '" 
            //     class="btn btn-sm btn-primary" title="View">
            //     <i class="fa fa-eye"></i>
            // </a>';

        return $actions;
    }
}
