<?php

namespace App\Http\Controllers\Api;

use App\Models\Salary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\SalaryTransformer;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $salaries = Salary::select('salaries.*');

        if ($request->filled('search')) {
            $salaries = $salaries->TextSearch($request->input('search'));
        }
        // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
        // case we override with the actual count, so we should return 0 items.
        $offset = (($salaries) && ($request->get('offset') > $salaries->count())) ? $salaries->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $allowed_columns = [
            'riding_company_id',
            'driver_id',
            'user_id',
            'amount_paid',
            'from_date',
            'to_date',
            'created_at'
        ];
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
        $salaries = $salaries->orderBy($sort, $order);

        $total = $salaries->count();
        $salaries = $salaries->skip($offset)->take($limit)->get();
        return (new SalaryTransformer)->transformSalary($salaries, $total);
    }
}
