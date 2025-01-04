<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use Illuminate\Support\Collection; // Change this import to Support Collection

class TowingTransformer
{
    public function transformgrid(Collection $towingsData, $total) // Updated to Support Collection
    {
        $array = [];

        foreach ($towingsData as $towingData_value) {
            $array[] = self::transform($towingData_value);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transform($towingData_value)
    {
        if ($towingData_value) {
            $approveButton = ''; 
            if ($towingData_value->failed_towing == 1) {
                $approveButton = '<button class="btn btn-sm bg-blue approved-btn" data-id="' . $towingData_value->id . '">Approve</button>';
            }
            $array = [
                'asset_name' => $towingData_value->asset_name ?? 'N/A',
                'username' => $towingData_value->username ?? 'N/A',
                'location' => $towingData_value->location ?? 'Location not available',
                'towing_date' => $towingData_value->towing_date, 'date' ?? 'N/A',
                'reason' => $towingData_value->reason ?? '',
                'failed_reason' => $towingData_value->failed_reason ?? '',
                'created_at' => Helper::getFormattedDateObject($towingData_value->created_at, 'datetime'),
                'updated_at' => Helper::getFormattedDateObject($towingData_value->updated_at, 'datetime'),
                'approve' => $approveButton,
            ];
            
            return $array;
        }

        return [];
    }
}
