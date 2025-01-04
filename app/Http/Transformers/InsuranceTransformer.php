<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\Insurance;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class InsuranceTransformer
{
    public function transformInsurances(Collection $insurances, $total)
    {
        $array = [];
        foreach ($insurances as $insurance) {
            $array[] = self::transformInsurance($insurance);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformInsurance(Insurance $insurance = null)
    {

        // We only ever use item_count for categories in this transformer, so it makes sense to keep it
        // simple and do this switch here.


        if ($insurance) {
            // dd($insurance->supplierInfo);
            $array = [
                'id'             => (int) $insurance->id,

                'asset_id' => $insurance->asset ? 
    '<a href="'.url("/insuranceDetail/".$insurance->id).'" data-tooltip="true" title="" data-original-title="asset"><i class="fas fa-barcode text-blue "></i> '.$insurance->asset->name.' ('.$insurance->asset->asset_tag.')</a>' 
    : 'No asset available',



                // 'asset_id'       => $insurance->asset->name,
                'vendor_id'      => ($insurance->supplierInfo<>null)? $insurance->supplierInfo->name : '',
                'towingsavailable' => Helper::getNumberFormate($insurance->towingsavailable),
                'insurance_date' => Helper::getDateFormate($insurance->insurance_date),
                'insurance_from' => Helper::getDateFormate($insurance->insurance_from),
                'insurance_to'   => Helper::getDateFormate($insurance->insurance_to),
                'amount'         => Helper::getNumberFormate($insurance->amount),
                'premium_type'   => Helper::getPremiumTypeLabel()[$insurance->premium_type],
                'cost'           => Helper::getNumberFormate($insurance->cost),
                'no_of_drivers_allowed' => $insurance->no_of_drivers_allowed,
                'driver_cost'    => Helper::getNumberFormate($insurance->driver_cost),
                'created_at'     => Helper::getFormattedDateObject($insurance->created_at, 'datetime'),
                'updated_at'     => Helper::getFormattedDateObject($insurance->updated_at, 'datetime'),
                'created_by'     => $insurance->created_by,
                'updated_by'     => $insurance->updated_by,
            ];


            $permissions_array['available_actions'] = [
                'update' => Gate::allows('update', Insurance::class),
                'delete' => Gate::allows('delete', Insurance::class),
            ];


            $array += $permissions_array;

            return $array;
        }
    }
}
