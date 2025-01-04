<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Transformers\InsuranceTransformer;
use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Http\Requests\ImageUploadRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $this->authorize('view', Insurance::class);
    $allowed_columns = [
        'id',
        'asset_id',
        'vendor_id',
        'towingsavailable',
        'insurance_date',
        'insurance_from',
        'insurance_to',
        'amount',
        'premium_type',
        'cost',
        'no_of_drivers_allowed',
        'driver_cost',
    ];

    // Start the query builder for insurances
    $insurances = Insurance::select(
        'id',
        'asset_id',
        'vendor_id',
        'towingsavailable',
        'insurance_date',
        'insurance_from',
        'insurance_to',
        'amount',
        'premium_type',
        'cost',
        'no_of_drivers_allowed',
        'driver_cost',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    )->with('asset');

    if (isset($request->target) && $request->target == 'upcoming_expires') {
        // Get the current date
        $currentDate = Carbon::now();
        // Add 1 month to the current date
        $endDate = $currentDate->copy()->addMonth();
        $insurances = $insurances->whereBetween('insurance_to', [$currentDate, $endDate]);
    }

    if ($request->filled('search')) {
        $insurances = $insurances->TextSearch($request->input('search'));
    }

    // Calculate total before applying pagination
    $total = $insurances->count();

    // Make sure the offset and limit are valid integers
    $offset = (int) $request->input('offset', 0);
    $limit = (int) $request->input('limit', config('app.max_results', 500));

    // Ensure offset does not exceed the total
    $offset = min($offset, $total);

    // Ensure limit does not exceed the maximum allowed limit
    if ($limit > config('app.max_results', 500)) {
        $limit = config('app.max_results', 500);
    }

    // Apply sorting
    $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
    $sort = in_array($request->input('sort'), $allowed_columns) ? $request->input('sort') : 'asset_id';
    $insurances->orderBy($sort, $order);

    // Apply pagination
    $insurances = $insurances->skip($offset)->take($limit)->get();

    // Return the transformed insurances
    return (new InsuranceTransformer)->transformInsurances($insurances, $total);
}

    
       public function upcommingExpired(Request $request)
    {
        $this->authorize('view', Insurance::class);
        $allowed_columns = [
            'id',
            'asset_id',
            'vendor_id',
            'towingsavailable',
            'insurance_date',
            'insurance_from',
            'insurance_to',
            'amount',
            'premium_type',
            'cost',
            'no_of_drivers_allowed',
            'driver_cost',
        ];


        $insurances = Insurance::select([
            'id',
            'asset_id',
            'vendor_id',
            'towingsavailable',
            'insurance_date',
            'insurance_from',
            'insurance_to',
            'amount',
            'premium_type',
            'cost',
            'no_of_drivers_allowed',
            'driver_cost',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            ]);


            // Get the current date
            $currentDate = Carbon::now();

            // Add 1 month to the current date
            $endDate = $currentDate->copy()->addMonth();

            $insurances =  $insurances->whereBetween('insurance_to', [$currentDate, $endDate]);


        if ($request->filled('search')) {
            $insurances = $insurances->TextSearch($request->input('search'));
        }

        // Make sure the offset and limit are actually integers and do not exceed system limits
        $offset = ($request->input('offset') > $insurances->count()) ? $insurances->count() : abs($request->input('offset'));
        // $limit = app('api_limit_value');
          $limit = config('app.max_results');


        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? $request->input('sort') : 'asset_id';
        $insurances->orderBy($sort, $order);
        

        $total = $insurances->count();
        
        $insurances = $insurances->skip($offset)->take($limit)->get();


        return (new InsuranceTransformer)->transformInsurances($insurances, $total);

    }


}
