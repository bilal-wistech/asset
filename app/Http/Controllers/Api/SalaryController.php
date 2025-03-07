<?php

namespace App\Http\Controllers\Api;

use App\Models\Salary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\SalaryTransformer;
use Illuminate\Support\Facades\DB;
class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $salaries = Salary::query()
            ->select(
                'id',
                'driver_id',
                DB::raw('SUM(amount_paid) as total_amount_paid'),
                DB::raw('MIN(from_date) as from_date'),
                DB::raw('MAX(to_date) as to_date'),
                'user_id',
                'created_at'
            )
            ->groupBy('driver_id');
        
        if ($request->filled('search')) {
            $salaries = $salaries->TextSearch($request->input('search'));
        }
    
        // Allowed columns for sorting
        $allowed_columns = [
            'driver_id',
            'total_amount_paid',
            'from_date',
            'to_date'
        ];
        
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? $request->input('sort') : 'from_date';
        $salaries = $salaries->orderBy($sort, $order);
    
        // Pagination
        $offset = $request->get('offset', 0);
        $limit = min($request->input('limit', config('app.max_results')), config('app.max_results'));
        
        $total = $salaries->count();
        $salaries = $salaries->skip($offset)->take($limit)->get();
    
        return (new SalaryTransformer)->transformSalary($salaries, $total);
    }


}

