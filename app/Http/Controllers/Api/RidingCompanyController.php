<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RidingCompany;
use App\Http\Transformers\RidingCompanyTransformer;
class RidingCompanyController extends Controller
{
    public function index(Request $request){
        $ridingCompanies = RidingCompany::select('riding_companies.*');

      if ($request->filled('search')) {
         $ridingCompanies = $ridingCompanies->TextSearch($request->input('search'));
      }
      // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
      // case we override with the actual count, so we should return 0 items.
      $offset = (($ridingCompanies) && ($request->get('offset') > $ridingCompanies->count())) ? $ridingCompanies->count() : $request->get('offset', 0);

      // Check to make sure the limit is not higher than the max allowed
      ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

      $allowed_columns = [
        'name','status'
      ];
      $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
      $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
      $ridingCompanies = $ridingCompanies->orderBy($sort, $order);

      $total = $ridingCompanies->count();
      $ridingCompanies = $ridingCompanies->skip($offset)->take($limit)->get();
      return (new RidingCompanyTransformer)->transformRidingCompany($ridingCompanies, $total);
    }
}
