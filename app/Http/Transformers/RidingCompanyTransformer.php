<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class RidingCompanyTransformer
{
    public function transformRidingCompany($ridingCompanies, $total)
    {
        $array = [];
        foreach ($ridingCompanies as $company) {

            $array[] = self::transform($company);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }
    public function transform($company)
    {

        //  dd($expData_value);

        if ($company) {


            $array = [
                'id' => $company->id,
                'name' => $company->name ?? '',
                'status' => $company->status,
                'actions' => $this->getActionButtons($company)
            ];

            

            return $array;
        }
    }


    private function getActionButtons($company)
    {
        $actions = '';

        // Edit Button
        $actions .= '<a href="' . route('riding-companies.edit', $company) . '" 
            class="btn btn-sm btn-info" title="Edit">
            <i class="fa fa-pencil"></i>
            </a>';

        // Delete Button (Proper Method)
        $actions .= '<form action="' . route('riding-companies.destroy', $company) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                            onclick="return confirm(\'Are you sure you want to delete this?\')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>';

        return $actions;
    }

}
