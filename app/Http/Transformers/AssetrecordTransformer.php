<?php
namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\Actionlog;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class AssetrecordTransformer
{

    public function transformAssetrecord ( $total, $actionlogs)
    {
        // return $actionlogs;
        $array = array();
        $settings = Setting::getSettings();
        
        foreach ($actionlogs as $logs) {
            $results =Actionlog::where('id', '>', $logs->id)
       
            ->where('item_id', $logs->item_id )
             ->where('target_id', $logs->target_id )
            ->where('action_type', 'checkin from')->orderBy('created_at')
            ->first();
            //   return response()->json([$results , $logs]); ;
            $array[] = self::transformActionlog($logs, $settings , $results );
            // return $array;
        }
        return (new DatatablesTransformer)->transformDatatables($array, $total );
    }

   
    public function transformActionlog ( $logs, $settings = null , $results)
    {
            // return response()->json([ $results , $actionlog ]); 
       
        if( $results != null)
        {
            // dd($results->action_date);
            $array = [
           
                'from' => $logs->created_at ? Helper::convertDateTimeFormat($logs->created_at ) :null ,
                'to' => $results->created_at ? Helper::convertDateTimeFormat($results->created_at ) :null ,
                'target' => ($results->target_id) ? [
                    'id' => (int) $results->target_id,
                    'name' => ($results->targetType()=='user') ? e($results->target->getFullNameAttribute()) : e($results->target->getDisplayNameAttribute()),
                    'type' => e($results->targetType()),
                ] : null,
                'note'     => ($results->note) ? e($results->note): null ,
                 'location' => ($logs->location) ? [
                    'id' => (int) $logs->location->id,
                    'name' => e($logs->location->name),
                ] : ['location is not available'],
                
               
    
            ];
           
            return $array;
        } 
        else
         {
            if($logs->target_id)
            {
                $array = [
           
                    'from' => $logs->created_at  ? Helper::convertDateTimeFormat($logs->created_at ) :null ,
                    'to' => 'not checkin yet',
                    
                    'target' => ($logs->target_id) ? [
                        'id' => (int) $logs->target_id,
                        'name' => ($logs->targetType()=='user') ? e($logs->target->getFullNameAttribute()) : e($logs->target->getDisplayNameAttribute()),
                        'type' => e($logs->targetType()),
                    ] : null,
                    'note'     => ($logs->note) ? e($logs->note): null ,
                    
                   
        
                ];
                return $array;
            } else
            {
                $array = [
           
                    'from' => $logs->created_at  ? Helper::convertDateTimeFormat($logs->created_at ) :null ,
                    'to' => 'not checkout yet',
                    
                   
                    
                   
        
                ];
                return $array;
            }
          
         }


    }



    public function transformCheckedoutrecord (Collection $accessories_users, $total)
    {

        $array = array();
        foreach ($accessories_users as $user) {
            $array[] = (new UsersTransformer)->transformUser($user);
        }
        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }



}