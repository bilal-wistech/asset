<?php

namespace App\Http\Transformers;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\handover;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

class CashHandoverDetailTransformer
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
                'id' => 'CH-' . $handover->id,
                'handover_by' => $handover->handoverByUser ? $handover->handoverByUser->username : 'User not available',
                'handover_to' => $handover->handoverToUser ? $handover->handoverToUser->username : 'User not available',
                'handover_date' => Carbon::parse($handover->handover_date)->format('d-m-Y'),
                'total_amount' => $handover->total_amount,
                'is_verified' => $handover->is_verified == 1
                    ? '<span class="badge badge-success">Verified</span>'
                    : '<span class="badge badge-danger">Not Verified</span>',
                'actions' => $this->getActionButtons($handover)
            ];

            return $array;
        }

        return [];
    }

    private function getActionButtons($handover)
    {
        $actions = '';

        if (Auth::user()->isSuperUser() || auth()->id() === $handover->handover_to) {
            $actions .= '<a href="' . route('cash-handover.view', $handover->id) . '" 
                   class="btn btn-sm btn-info" title="View">
                   <i class="fa fa-eye"></i>
                </a>';
        }

        return $actions;
    }
}
