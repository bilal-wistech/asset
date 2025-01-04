<?php

namespace App\Http\Transformers;
use App\Models\Deduction;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

class DeductionTransformer
{
    /**
     * Transform a collection of deductions.
     *
     * @param Collection $deductions
     * @param int $total
     * @return array
     */
    public function transformDeductions($deductions, $total)
    {
        $array = [];
        foreach ($deductions as $deduction) {
            //dd($deduction);
            $array[] = self::transform($deduction);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    /**
     * Transform a single deduction.
     *
     * @param \App\Models\Deduction $deduction
     * @return array
     */
    public function transform($deduction)
    {
        

        if ($deduction) {
            $array = [
                'id' => $deduction->id,
                'deduction_date' => $deduction->deduction_date ? Helper::getFormattedDateObject($deduction->deduction_date, 'datetime') : null,
                'username' => $deduction->user ? $deduction->user->username : 'User not available',
                'amount' => $deduction->amount,
                'reason' => $deduction->reason_name ?? 'No reason provided',
                'note' => $deduction->note ?? 'No notes available',
                'created_at' => $deduction->created_at ? Helper::getFormattedDateObject($deduction->created_at, 'datetime') : null,
            ];

            $permissions_array['available_actions'] = [
                'update' => Gate::allows('update', \App\Models\Deduction::class),
                'delete' => Gate::allows('delete', \App\Models\Deduction::class),
            ];

            $array += $permissions_array;

            return $array;
        }

        return [];
    }
}
