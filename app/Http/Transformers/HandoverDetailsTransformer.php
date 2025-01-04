<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\HandoverImages;
use Illuminate\Database\Eloquent\Collection;

class HandoverDetailsTransformer
{
    public function transformHandoverDetails(Collection $handover_details, $total)
    {

        $array = [];
        foreach ($handover_details as $handover_detail) {
            $array[] = $this->transformHandoverDetail($handover_detail);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformHandoverDetail(HandoverImages $handover_detail = null)
    {
        if ($handover_detail) {
            $array = [
                'id' => (int) $handover_detail->id,
                'asset_name' => e($handover_detail->asset->name ?? ''),
                'asset_tag' => e($handover_detail->asset->asset_tag ?? ''),
                'image' => ($handover_detail->images) ? url(e($handover_detail->images)) : null,
                'notes' => e($handover_detail->notes ?? ''),
                'checkin_date' => ($handover_detail->checkin_date ?? ''),
                'user' => ($handover_detail->user) ? ($handover_detail->user->username ?? e($handover_detail->user->first_name).' '.e($handover_detail->user->last_name)) : '',
                'reason' => $handover_detail->reason->title ?? '',
                'created_at' => Helper::getFormattedDateObject($handover_detail->created_at, 'datetime'),
                'updated_at' => Helper::getFormattedDateObject($handover_detail->updated_at, 'datetime'),

            ];
            return $array;
        }
    }
}
