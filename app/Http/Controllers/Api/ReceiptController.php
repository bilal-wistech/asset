<?php

namespace App\Http\Controllers\Api;
use App\Http\Transformers\ReceiptTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\ReceiptDetail;
use App\Helpers\Helper;

class ReceiptController extends Controller
{
    public function index(Request $request)
   {

      $receipts = Receipt::with(['receiptDetails','user'])->whereNull('deleted_at');
      if ($request->filled('search')) {
         $receipts = $receipts->TextSearch($request->input('search'));
      }
      $offset = (($receipts) && ($request->get('offset') > $receipts->count())) ? $receipts->count() : $request->get('offset', 0);

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
      $receipts = $receipts->orderBy($sort, $order);

      $total = $receipts->count();
      $receipts = $receipts->skip($offset)->take($limit)->get();
     
      return (new ReceiptTransformer)->transformReceipts($receipts, $total);
   }
    
}
