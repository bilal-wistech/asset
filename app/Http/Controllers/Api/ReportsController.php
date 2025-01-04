<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ActionlogsTransformer;
use App\Http\Transformers\AssetrecordTransformer;
use Illuminate\Support\Facades\Auth;
use App\Models\Actionlog;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Returns Activity Report JSON.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return View
     */
    public function index(Request $request)
    {
        $this->authorize('reports.view');

        $actionlogs = Actionlog::with('item', 'user', 'admin', 'target', 'location');

        if ($request->filled('search')) {
            $actionlogs = $actionlogs->TextSearch(e($request->input('search')));
        }

        if (($request->filled('target_type')) && ($request->filled('target_id'))) {
            $actionlogs = $actionlogs->where('target_id', '=', $request->input('target_id'))
                ->where('target_type', '=', 'App\\Models\\'.ucwords($request->input('target_type')));
        }

        if (($request->filled('item_type')) && ($request->filled('item_id'))) {
            $actionlogs = $actionlogs->where('item_id', '=', $request->input('item_id'))
                ->where('item_type', '=', 'App\\Models\\'.ucwords($request->input('item_type')));
        }

        if ($request->filled('action_type')) {
            $actionlogs = $actionlogs->where('action_type', '=', $request->input('action_type'))->orderBy('created_at', 'desc');
        }

        if ($request->filled('uploads')) {
            $actionlogs = $actionlogs->whereNotNull('filename')->orderBy('created_at', 'desc');
        }

        $allowed_columns = [
            'id',
            'created_at',
            'target_id',
            'user_id',
            'accept_signature',
            'action_type',
            'note',
        ];

        $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
        $order = ($request->input('order') == 'asc') ? 'asc' : 'desc';
        $offset = request('offset', 0);
        $total = $actionlogs->count();

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');


        $actionlogs = $actionlogs->orderBy($sort, $order)->skip($offset)->take($limit)->get();

        return response()->json((new ActionlogsTransformer)->transformActionlogs($actionlogs, $total), 200, ['Content-Type' => 'application/json;charset=utf8'], JSON_UNESCAPED_UNICODE);
    }
    
     public function records(Request $request)
    {
        $actionlogs = Actionlog::with('item', 'user', 'admin', 'target', 'location')->where('action_type', 'checkout')->where('item_id', '=', $request->input('item_id'));
        
       

        $allowed_columns = [
            'id',
            'created_at',
            'target_id',
            'user_id',
            'action_type',
            'note',
        ];

        $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
       
     
        $total = $actionlogs->count();

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');


        $actionlogs = $actionlogs->orderBy($sort)->get();
        // return response()->json([ $actionlogs]);
       
      
       
         
        

        return response()->json((new AssetrecordTransformer)->transformAssetrecord($total, $actionlogs ), 200, ['Content-Type' => 'application/json;charset=utf8'], JSON_UNESCAPED_UNICODE);
    }
    
     public function UserAssetRecord(Request $request)
    {
        $user =  Auth::guard('api')->user();
        $actionlogs = Actionlog::with('item', 'user', 'admin', 'target', 'location')->where('target_id', $user->id)
        ->where('action_type', 'checkout');
        
       

        $allowed_columns = [
            'id',
            'created_at',
            'target_id',
            'user_id',
            'action_type',
            'note',
        ];

        $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
       
     
        $total = $actionlogs->count();

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');


        $actionlogs = $actionlogs->orderBy($sort)->take($limit)->get();
// return $actionlogs;
        $arr = [];
        foreach ($actionlogs as $logs) {
            
            $results =Actionlog::where('id', '>', $logs->id)
       
            ->where('item_id', $logs->item_id )
             ->where('target_id', $logs->target_id )
            ->where('action_type', 'checkin from')->orderBy('created_at')
            ->first();
             // dd($results->action_date);

             if( $results != null)
             {
             $array = [
           
                'from' => $logs->created_at ? Helper::convertDateTimeUser($logs->created_at ) :null ,
                'to' => $results->created_at ? Helper::convertDateTimeUser($results->created_at ) :null ,
                'target' => ($results->target_id) ? [
                    'id' => (int) $results->target_id,
                    'name' => ($results->targetType()=='user') ? e($results->target->getFullNameAttribute()) : e($results->target->getDisplayNameAttribute()),
                    'type' => e($results->targetType()),
                ] : null,
                'note'     => ($results->note) ? e($results->note): null ,
                'item' => ($results->item) ? [
                    'id' => (int) $results->item->id,
                    'name' => ($results->itemType()=='user') ? e($results->item->getFullNameAttribute()) : e($results->item->getDisplayNameAttribute()),
                    'type' => e($results->itemType()),
                    'serial' =>e($results->item->serial) ? e($results->item->serial) : null
                ] : null,
                
               
    
            ];
           
            $arr[] = $array;
        }else
        {
           if($logs->target_id)
           {
               $array = [
          
                   'from' => $logs->created_at  ? Helper::convertDateTimeUser($logs->created_at ) :null ,
                   'to' => 'not checkin yet',
                   
                   'target' => ($logs->target_id) ? [
                       'id' => (int) $logs->target_id,
                       'name' => ($logs->targetType()=='user') ? e($logs->target->getFullNameAttribute()) : e($logs->target->getDisplayNameAttribute()),
                       'type' => e($logs->targetType()),
                   ] : null,
                   'note'     => ($logs->note) ? e($logs->note): null ,
                   'item' => ($logs->item) ? [
                    'id' => (int) $logs->item->id,
                    'name' => ($logs->itemType()=='user') ? e($logs->item->getFullNameAttribute()) : e($logs->item->getDisplayNameAttribute()),
                    'type' => e($logs->itemType()),
                    'serial' =>e($logs->item->serial) ? e($logs->item->serial) : null
                ] : null,
                   
                  
       
               ];
            //    return $array;
            $arr[] = $array;
           } else
           {
               $array = [
          
                   'from' => $logs->created_at  ? Helper::convertDateTimeUser($logs->created_at ) :null ,
                   'to' => 'not checkout yet',
                   
                  
                   
                  
       
               ];
            //    return $array;
            $arr[] = $array;
           }
        
        }
        
    }

    return response()->json($arr);
        // return $actionlogs;

    }
}
