<?php

namespace App\Http\Controllers\Api;

use App\Models\Receipt;
use App\Models\CashHandover;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Transformers\CashHandoverTransformer;
use App\Http\Transformers\CashHandoverDetailTransformer;

class CashHandoverController extends Controller
{
    public function index(Request $request)
    {

        $cashHandover = Receipt::with(['receiptDetails', 'user'])
            ->where('deduction_way', 'cash')
            ->where('added_by', Auth::user()->id)
            ->where('handed_over', 0)
            ->whereNull('deleted_at');
        if ($request->filled('search')) {
            $cashHandover = $cashHandover->TextSearch($request->input('search'));
        }
        $offset = (($cashHandover) && ($request->get('offset') > $cashHandover->count())) ? $cashHandover->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $allowed_columns = [
            'date',
            'deduction_way',
            'created_at',
            'receipt_id',
            'total_amount'
        ];
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
        $cashHandover = $cashHandover->orderBy($sort, $order);

        $total = $cashHandover->count();
        $cashHandover = $cashHandover->skip($offset)->take($limit)->get();

        return (new CashHandoverTransformer)->transformCashHandover($cashHandover, $total);
    }
    public function cashHandover(Request $request)
    {

        $cashHandover = CashHandover::with(['receipts', 'handoverByUser', 'handoverToUser']);
        if ($request->filled('search')) {
            $cashHandover = $cashHandover->TextSearch($request->input('search'));
        }
        $offset = (($cashHandover) && ($request->get('offset') > $cashHandover->count())) ? $cashHandover->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $allowed_columns = [
            'total_amount',
            'handover_by',
            'handover_to',
            'handover_date'
        ];
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
        $cashHandover = $cashHandover->orderBy($sort, $order);

        $total = $cashHandover->count();
        $cashHandover = $cashHandover->skip($offset)->take($limit)->get();

        return (new CashHandoverDetailTransformer)->transformCashHandover($cashHandover, $total);
    }
}
