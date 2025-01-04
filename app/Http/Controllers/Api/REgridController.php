<?php

namespace App\Http\Controllers\Api;

use PDF; 
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\User;

use App\Helpers\Helper;
use App\Models\AddExpence;
use Illuminate\Http\Request;
use App\Models\TypeOfExpence;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Transformers\REgridTransformer;
use App\Http\Transformers\TowingTransformer;
use App\Http\Transformers\ExpenceTypeTransformer;


class REgridController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return \Illuminate\Http\Response
     */
    public function showtowingdata(Request $request)
{
    
    $towingsRequests = DB::table('towings_requests')
        ->leftJoin('assets', 'towings_requests.asset_id', '=', 'assets.id')
        ->leftJoin('users', 'towings_requests.user_id', '=', 'users.id')
        ->select(
            'towings_requests.*',
            'assets.asset_tag as asset_name',
            'users.username'
        )
        ->get(); 
    $offset = ($request->get('offset', 0) > $towingsRequests->count()) ? $towingsRequests->count() : $request->get('offset', 0);
    $limit = ($request->filled('limit') && $request->input('limit') <= config('app.max_results')) ? $request->input('limit') : config('app.max_results');
    $total = $towingsRequests->count();
    $towingsRequests = $towingsRequests->slice($offset, $limit);
    return (new TowingTransformer)->transformgrid($towingsRequests, $total);
}

    public function index($id)
    {
        // $this->authorize('view', Category::class);
 
        // return 'hello';
        $expence_data = AddExpence::where('user_id' , $id )->first();
           // dd($expence_data);
            return response()->json($expence_data);
         //   return (new REgridTransformer)->transformgridUser($expence_data );


    }
/**
     * @param Request $request
     * @param null $id
     * @return mixed
     */
    public function show(Request $request, $id = null)
    {
        
        $expense = AddExpence::select('add_expences.*')->with('type', 'asset', 'userData');
        $user_id = 0;

        if ($request->filled('start_date')) {
            $expense->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $expense->whereDate('created_at', '<=', $request->input('end_date'));
        }

        if ($id !== null && $id > 0) {
            $user_id = $id;
        } else if ($request->query('user_id') && $request->query('user_id') > 0) {
            $user_id = $request->query('user_id');
        }

        if ($user_id) {
            $expense = $expense->where('user_id', $user_id);
        }
        if ($request->has('export_pdf') && $request->export_pdf == 'true') {
           
            $expenses = $expense->get();
            $pdf = PDF::loadView('pdf.expense_images', compact('expenses')); // Create a view for the PDF
            return $pdf->stream('expenses.pdf'); 
     
        }
        
        $offset = (($expense) && ($request->get('offset') > $expense->count())) ? $expense->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');
        
        $total = $expense->count();
        $expense = $expense->skip($offset)->take($limit)->get();
        return (new REgridTransformer)->transformgrid($expense, $total);
    }

    public function approval($id, Request $request)
{
    $old_request = $request->all();
    $expense_data = AddExpence::find($id);
    $users = User::all();
    $expense_data->update(['approved' => 1]);

    $offset = $request->get('offset', 0);
    $limit = $request->get('limit', config('app.max_results'));

    // Return JSON response
    return response()->json([
        'old_request' => $old_request,
        'users' => $users,
        'total' => $expense_data->count(),
        'offset' => $offset,
        'limit' => $limit
    ]);
}

public function disapproval($id, Request $request)
{
    $old_request = $request->all();
    $users = User::all();
    $expense_data = AddExpence::find($id);
    $expense_data->update(['approved' => 0]);

    $offset = $request->get('offset', 0);
    $limit = $request->get('limit', config('app.max_results'));

    // Return JSON response
    return response()->json([
        'old_request' => $old_request,
        'users' => $users,
        'total' => $expense_data->count(),
        'offset' => $offset,
        'limit' => $limit
    ]);
}

    


    

    public function ShowExpenseType()
    {
      $type = TypeOfExpence::all();
      return (new ExpenceTypeTransformer)->transformtype($type );
    }

}