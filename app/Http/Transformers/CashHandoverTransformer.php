<?php

namespace App\Http\Transformers;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\handover;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

class CashHandoverTransformer
{
    /**
     * Transform a collection of cashHandover.
     *
     * @param Collection $cashHandover
     * @param int $total
     * @return array
     */
    public function transformCashHandover($cashHandover, $total)
    {
        $array = [];
        foreach ($cashHandover as $handover) {
            // dd($handover->receipt_id);
            $array[] = self::transform($handover);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    /**
     * Transform a single deduction.
     *
     * @param \App\Models\Deduction $handover
     * @return array
     */
    public function transform($handover)
    {
        if ($handover) {
            $array = [
                'state' => !$handover->handed_over, // Disable checkbox if handed over
                'id' => 'ADJ-' . $handover->receipt_id,
                'username' => $handover->user ? $handover->user->username : 'User not available',
                'date' => Carbon::parse($handover->date)->format('d-m-Y'),
                'total_amount' => $handover->total_amount,
                'handed_over' => $handover->handed_over,
                'actions' => $this->getActionButtons($handover)
            ];

            return $array;
        }

        return [];
    }

    private function getActionButtons($handover)
    {
        $actions = '';
        if ($handover->handed_over) {
            $actions .= '<a href="' . route('cash-handover.view', $handover->id) . '" 
                       class="btn btn-sm btn-info" title="View">
                       <i class="fa fa-eye"></i>
                    </a>';
        }
        return $actions;
    }
}
