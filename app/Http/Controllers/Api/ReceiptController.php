<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Models\Receipt;
use Illuminate\Http\Request;
use App\Models\ReceiptDetail;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ReceiptTransformer;
use App\Http\Transformers\SalaryCashTransformer;

class ReceiptController extends Controller
{
   public function index(Request $request)
   {

      $receipts = Receipt::with(['receiptDetails', 'user'])
         ->whereIn('deduction_way', ['cash', 'salary'])
         ->whereNull('deleted_at');
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
   public function SalaryCash(Request $request)
   {

      $salaryCash = Receipt::with(['receiptDetails','user'])
      ->where('deduction_way','salary cash')
      ->whereNull('deleted_at');
      if ($request->filled('search')) {
         $salaryCash = $salaryCash->TextSearch($request->input('search'));
      }
      $offset = (($salaryCash) && ($request->get('offset') > $salaryCash->count())) ? $salaryCash->count() : $request->get('offset', 0);

      // Check to make sure the limit is not higher than the max allowed
      ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

      $allowed_columns = [

            'date',
            'deduction_way',
            'created_at',
            'receipt_id',
            'total_amount',
            'salary_to_be_included_from',
            'salary_to_be_included_to'
      ];
      $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
      $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
      $salaryCash = $salaryCash->orderBy($sort, $order);

      $total = $salaryCash->count();
      $salaryCash = $salaryCash->skip($offset)->take($limit)->get();
     
      return (new SalaryCashTransformer)->transformSalaryCash($salaryCash, $total);
   }
}
