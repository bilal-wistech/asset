<?php

namespace App\Http\Transformers;
use App\Models\Receipt;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

class ReceiptTransformer
{
    /**
     * Transform a collection of receipts.
     *
     * @param Collection $receipts
     * @param int $total
     * @return array
     */
    public function transformReceipts($receipts, $total)
    {
        $array = [];
        foreach ($receipts as $receipt) {
            //dd($receipt->receipt_id);
            $array[] = self::transform($receipt);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    /**
     * Transform a single deduction.
     *
     * @param \App\Models\Deduction $receipt
     * @return array
     */
    public function transform($receipt)
    {
        if ($receipt) {
            $array = [
                'id' => 'ADJ-' .$receipt->receipt_id,
                'username' => $receipt->user ? $receipt->user->username : 'User not available',
                'deduction_way' => $receipt->deduction_way,
                'date' => $receipt->date ? Helper::getFormattedDateObject($receipt->date, 'datetime') : null,
                'created_at' => $receipt->created_at ? Helper::getFormattedDateObject($receipt->created_at, 'datetime') : null,
                'total_amount' => $receipt->receiptDetails->sum('payment')
            ];

            $permissions_array['available_actions'] = [
                'update' => Gate::allows('update', \App\Models\Receipt::class),
                'delete' => Gate::allows('delete', \App\Models\Receipt::class),
            ];

            $array += $permissions_array;

            return $array;
        }

        return [];
    }
}
?>

