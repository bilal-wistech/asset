<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\FineTransformer;

use Illuminate\Http\Request;
use App\Models\Fine;
use App\Models\FineType;
use App\Models\Asset;
use App\Helpers\Helper;

class FineController extends Controller
{
   public function index(Request $request)
   {
      $fines = Fine::select('fines.*')->with('asset', 'user', 'type', 'findLocation');

      if ($request->filled('search')) {
         $fines = $fines->TextSearch($request->input('search'));
      }
      // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
      // case we override with the actual count, so we should return 0 items.
      $offset = (($fines) && ($request->get('offset') > $fines->count())) ? $fines->count() : $request->get('offset', 0);

      // Check to make sure the limit is not higher than the max allowed
      ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

      $allowed_columns = [
         'fine_date',
         'fine_type',
         'asset_id',
         'user_id',
         'amount',
         'location',
         'note',
         'fine_image',
         'fine_number'
      ];
      $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
      $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
      $fines = $fines->orderBy($sort, $order);

      $total = $fines->count();
      $fines = $fines->skip($offset)->take($limit)->get();
      return (new FineTransformer)->transformfine($fines, $total);
   }

  
public function fineType(Request $request)
{
    $this->authorize('view', FineType::class);

    if (!$request->filled('name')) {
        return response()->json(Helper::formatStandardApiResponse('error', null, ['Name' => ['Name is required.']]));
    }

    // Validate 'amount' field
    if (!$request->filled('amount')) {
        return response()->json(Helper::formatStandardApiResponse('error', null, ['Amount' => ['Amount is required.']]));
    }

    $type = new FineType;
    $type->name = $request->name;
    $type->amount = $request->amount;
    
    if ($type->save()) {
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $type->id,
                'name' => $type->name
            ],
            'message' => 'New Accident Type is Saved Successfully.'
        ]);
    }
    
    return response()->json([
        'status' => 'error',
        'message' => 'There is an error in saving'
    ]);
}

}
