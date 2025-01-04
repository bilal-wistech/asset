<?php

namespace App\Http\Controllers\Api;
use App\Http\Transformers\DeductionTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deduction;
use App\Models\DeductionType;
use App\Helpers\Helper;

class DeductionController extends Controller
{
    public function index(Request $request)
   {
    //   $deductions = Deduction::
      $deductions = Deduction::select('deductions.*', 'deduction_types.name as reason_name')
      ->leftJoin("deduction_types", "deductions.reason" , '=', 'deduction_types.id')
      ->with(['user']);
      if ($request->filled('search')) {
         $deductions = $deductions->TextSearch($request->input('search'));
      }

      // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
      // case we override with the actual count, so we should return 0 items.
      $offset = (($deductions) && ($request->get('offset') > $deductions->count())) ? $deductions->count() : $request->get('offset', 0);

      // Check to make sure the limit is not higher than the max allowed
      ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

      $allowed_columns = [
         'deduction_date',
            'user_id',
            'amount',
            'reason',
            'note',
      ];
      $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
      $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
      $deductions = $deductions->orderBy($sort, $order);

      $total = $deductions->count();
      $deductions = $deductions->skip($offset)->take($limit)->get();
    //   dd($deductions);
      return (new DeductionTransformer)->transformDeductions($deductions, $total);
   }

   public function deductionType(Request $request)
{
    $this->authorize('view', DeductionType::class);

    if (!$request->filled('name')) {
        return response()->json(Helper::formatStandardApiResponse('error', null, ['Name' => ['Name is required.']]));
    }

    

    $type = new DeductionType;
    $type->name = $request->name;
    
    
    if ($type->save()) {
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $type->id,
                'name' => $type->name
            ],
            'message' => 'New Deduction Type is Saved Successfully.'
        ]);
    }
    
    return response()->json([
        'status' => 'error',
        'message' => 'There is an error in saving'
    ]);
}
    
}
