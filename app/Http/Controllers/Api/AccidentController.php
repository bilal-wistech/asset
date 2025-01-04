<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\AccidentTransformer;

use Illuminate\Http\Request;
use App\Models\Accident;
use App\Models\AccidentType;
use App\Models\Asset;
use App\Helpers\Helper;

class AccidentController extends Controller
{
    public function index(Request $request)
    {
        $accidents = Accident::select('accidents.*')->with('asset', 'user', 'type', 'findLocation');
        if ($request->filled('search')) {
            $accidents = $accidents->TextSearch($request->input('search'));
        }

        // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
        // case we override with the actual count, so we should return 0 items.
        $offset = (($accidents) && ($request->get('offset') > $accidents->count())) ? $accidents->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $allowed_columns = [
            'accident_date',
            'accident_type',
            'asset_id',
            'user_id',
            'amount',
            'location',
            'note',
            'accident_image',
            'accident_number',
            'claim_opening',
            'damages_amount',
            'responsibility',
            'responsibility_amount',
            'relevant_files'
        ];
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
        $accidents = $accidents->orderBy($sort, $order);

        $total = $accidents->count();
        $accidents = $accidents->skip($offset)->take($limit)->get();
        return (new AccidentTransformer)->transformaccident($accidents, $total);
    }

    public function accidentType(Request $request)
    {
        $this->authorize('view', AccidentType::class);

        if (!$request->filled('name')) {
            return response()->json(Helper::formatStandardApiResponse('error', null, ['Name' => ['Name is required.']]));
        }

        // Validate 'amount' field
        if (!$request->filled('amount')) {
            return response()->json(Helper::formatStandardApiResponse('error', null, ['Amount' => ['Amount is required.']]));
        }

        $type = new AccidentType;
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
