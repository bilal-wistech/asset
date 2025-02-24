<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashAdjustment;

class CashAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $cashAdjustments = CashAdjustment::query()
        ->select(
            'id',
            'driver_id',
            'user_id',
            'amount'
            'created_at',
            'updated_at'
        );
        
    
    if ($request->filled('search')) {
        $salaries = $salaries->TextSearch($request->input('search'));
    }

    // Allowed columns for sorting
    $allowed_columns = [
        'driver_id',
        'user_id'
        'amount',
        'created_at',
        'updated_at'
    ];
    
    $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
    $sort = in_array($request->input('sort'), $allowed_columns) ? $request->input('sort') : 'created_at';
    $salaries = $salaries->orderBy($sort, $order);

    // Pagination
    $offset = $request->get('offset', 0);
    $limit = min($request->input('limit', config('app.max_results')), config('app.max_results'));
    
    $total = $salaries->count();
    $salaries = $salaries->skip($offset)->take($limit)->get();

    return (new SalaryTransformer)->transformSalary($cashAdjustments, $total);
    }

}
