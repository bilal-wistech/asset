<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\Accident;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class AccidentTransformer
{
    public function transformaccident($accidents, $total)
    {
        $array = [];
        foreach ($accidents as $accident) {

            $array[] = self::transform($accident);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }
    public function transform($accident)
    {

        //  dd($expData_value);

        if ($accident) {


            $array = [
                'id' => $accident->id,
                'accident_number' => $accident->accident_number ? $accident->accident_number : '',
                'asset_name' => $accident && $accident->asset
                    ? "<a href=\"http://project_assets.test/hardware/{$accident->asset->id}\">" . e($accident->asset->name ?? $accident->asset->asset_tag ?? '') . "</a>"
                    : '',

                'username' => $accident->user != null ? $accident->user->username : 'Username is not available',
                'responsibility' => $accident->responsibility ?? '',
                'claimable' => Helper::getClaimableLabel()[$accident->claimable],
                'claim_opening' => $accident->claim_opening ?? 0,
                'damages_amount' => $accident->damages_amount ?? 0,
                'amount' => $accident->responsibility_amount,
                'recieved' => Helper::showMessage($accident->recieved_by_user),
                'accident_date' => $accident->accident_date ? Helper::getFormattedDateObject($accident->accident_date, 'datetime') : null,
                'created_at' => Helper::getFormattedDateObject($accident->created_at, 'datetime'),



            ];

            $permissions_array['available_actions'] = [
                'update' => Gate::allows('update', Accident::class),
                'delete' => Gate::allows('delete', Accident::class),
            ];

            $array += $permissions_array;


            return $array;
        }
    }
}
