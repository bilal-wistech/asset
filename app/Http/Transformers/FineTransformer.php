<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class FineTransformer
{
    public function transformfine($fines, $total)
    {
        $array = [];
        foreach ($fines as $fine) {

            $array[] = self::transform($fine);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }
    public function transform($fine)
    {

        //  dd($expData_value);

        if ($fine) {


            $array = [
                'id' => $fine->id,
                'fine_number' => $fine->fine_number ? $fine->fine_number : '',
                'asset_name' => $fine && $fine->asset
    ? "<a href=\"http://project_assets.test/hardware/{$fine->asset->id}\">" . ($fine->asset->name ?? $fine->asset->asset_tag ?? '') . "</a>"
    : ($fine->asset->name ?? $fine->asset->asset_tag ?? ''),


                
                'username' => $fine->user != null ? $fine->user->username : 'Username is not available',
                'fine_type' => $fine->type && $fine->type->name != null ? $fine->type->name : 'not available',
                'amount' => $fine->amount,
                'recieved' => Helper::showMessage($fine->recieved_by_user),
                'fine_date' => $fine->fine_date ? Helper::getFormattedDateObject($fine->fine_date, 'datetime') : null,
                'created_at' => Helper::getFormattedDateObject($fine->created_at, 'datetime'),



            ];

            $permissions_array['available_actions'] = [
                'update' => Gate::allows('update', Fine::class),
                'delete' => Gate::allows('delete', Fine::class),
            ];

            $array += $permissions_array;


            return $array;
        }
    }
}
