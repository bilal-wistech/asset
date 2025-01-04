<?php

namespace App\Http\Controllers\Api;
use Exception;
use App\Models\Fine;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Location;
use App\Models\Insurance;
use App\Models\BikeQuestion;
use Illuminate\Http\Request;
use App\Models\TowingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Transformers\LocationsTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Transformers\SelectlistTransformer;

class LocationsController extends Controller
{
    /** 
     * Display a listing of the resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return \Illuminate\Http\Response
     */


     public function updateFines(Request $request)
     {
         $fine_id = $request->input('fine_id');
         
         if (!$fine_id) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Fine ID is required.',
             ], 400);
         }
     
         try {
             $fine = Fine::where('id', $fine_id)->first();
             if (!$fine) {
                 return response()->json([
                     'status' => 'error',
                     'message' => 'No fines found against this fine_id.',
                 ], 404);
             }
     
             // Update the fine record
             $updated = Fine::where('id', $fine_id)->update(['recieved_by_user' => 1]);
     
             if (!$updated) {
                 return response()->json([
                     'status' => 'error',
                     'message' => 'No fines were updated, please check the database.',
                 ], 404);
             }
     
             return response()->json([
                 'status' => 'success',
                 'message' => 'Notification Received By User Successfully!',
             ]);
         } catch (\Exception $e) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'An unexpected error occurred.',
                 'details' => $e->getMessage(),
             ], 500);
         }
     }
     
     
    public function getUserFines(Request $request)
    {
        $user_id = $request->input('user_id');

        // Check if user_id is provided
        if (!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID is required.',
            ], 400);
        }

        // Retrieve fines for the specified user_id with notification set to 1
        $fines = DB::table('fines as f')
        ->leftJoin('fine_types as ft', 'f.fine_type', '=', 'ft.id')
        ->leftJoin('assets as a', 'f.asset_id', '=', 'a.id')
        ->leftJoin('locations as l', 'f.location', '=', 'l.id')
        ->select('f.*', 'ft.name as fine_type_name', 'a.asset_tag as asset_name', 'l.name as location_name')
        ->where('f.user_id', $user_id)
        ->where('f.notification', 1)
        ->get();

        // Check if any fines are found
        if ($fines->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No notification found.',
            ], 404);
        }

        // Return the fines if found
        return response()->json([
            'status' => 'success',
            'data' => $fines,
        ]);
    }

 public function updateProfile(Request $request, $id)
    {
        //dd($request->files);
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        try {
            $validatedData = $request->validate([
                'username' => 'nullable|string|max:255',
                'email' => 'nullable|string|max:255',
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'id_card_front' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'id_card_back' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'driving_license_local' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'driving_license_international' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'maltese_driving_license' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'taxi_tag' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'taxi_tag_back' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'driving_license_local_back' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'driving_license_international_back' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'maltese_driving_license_back' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,webp,svg,pdf',
                'expiry_date_id_card' => 'nullable|date',
                'expiry_date_taxi_tag' => 'nullable|date',
                'expiry_date_driving_license_international' => 'nullable|date',
                'expiry_date_driving_license_local' => 'nullable|date',
                'expiry_date_maltese_license' => 'nullable|date',
            ]);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // es function ko check krein.
                app(ImageUploadRequest::class)->handleImages($user, 600, 'avatar', 'avatars', 'avatar');
            }

            // Handle document uploads
            $documents = [
                'id_card_front',
                'id_card_back',
                'driving_license_local',
                'driving_license_international',
                'maltese_driving_license',
                'taxi_tag',
                'taxi_tag_back',
                'driving_license_local_back',
                'driving_license_international_back',
                'maltese_driving_license_back',
            ];
            foreach ($documents as $field) {

                if ($request->hasFile($field)) {
                    // Upload the file and get the file path
                    $file = $request->file($field);
                    $file_name = time() . '-' . $file->getClientOriginalName();
                    $file_path = "user_documents/" . $file_name;
                    
                    $file->move(public_path("user_documents"), $file_name);

                    // $filePath = $file->storeAs('user_documents', $file->getClientOriginalName(), 'public');

                    // Update the corresponding user column with the file path
                    $user->{$field} = $file_path;
                   
                    $user->save();
                }

                unset($validatedData[$field]);
            }

            

           
            // Update user data
            $user->update($validatedData);

            return response()->json([
                'message' => 'User updated successfully!',
                'user' => $user
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the user.'], 500);
        }
    }

 public function getUserProfileData($user_id = null)
    {

        if (!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID is required.',
            ], 400);
        }


        $users_data = DB::table('users')
            ->select(
                'id',
                'email',
                'expiry_date_id_card',
                'expiry_date_taxi_tag',
                'expiry_date_driving_license_international',
                'expiry_date_driving_license_local',
                'expiry_date_maltese_license',
                'username',
                'first_name',
                'last_name',
                'phone',
                'avatar',
                'id_card_front',
                'id_card_back',
                'driving_license_local',
                'driving_license_international',
                'maltese_driving_license',
                'taxi_tag',
                'taxi_tag_back',
                'driving_license_local_back',
                'driving_license_international_back',
                'maltese_driving_license_back'
            )
            ->where('id', $user_id)
            ->first();

        if ($users_data) {
            return response()->json([
                'status' => 'success',
                'users_data' => $users_data,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No record found for the provided asset_id',
            ], 404);
        }
    }

     public function storetowingdata(Request $request)
     {
         try {
     
             $validated = $request->validate([
                 'asset_id' => 'required|integer',
                 'location' => 'required|string|max:255',
                 'user_id' => 'required|integer',
                 'reason' => 'required|string|max:255',
                 'towing_date' => 'required|date',
             ]);
             $towingRequest = TowingRequest::create([
                 'asset_id' => $validated['asset_id'],
                 'location' => $validated['location'],
                 'user_id' => $validated['user_id'],
                 'reason' => $validated['reason'],
                 'towing_date' => $validated['towing_date'],
             ]);
             $insurance = Insurance::where('asset_id', $validated['asset_id'])->first();
             
             if ($insurance) {
                 
                 if ($insurance->towingsavailable > 0) {
                     $insurance->towingsavailable -= 1;
     
                     
                     if ($insurance->towingsavailable == 0) {
                         $insurance->notification = 1;
                     }
                 } else {
                    
                     $insurance->paidtowings += 1;
                     
                 }
                 $insurance->save();
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'No insurance record found for the provided asset',
                 ], 404);
             }
             return response()->json([
                 'status' => 'success',
                 'message' => 'Towing request created successfully',
                 'data' => $towingRequest,
             ], 201);
     
         } catch (ValidationException $e) {
             // Handle validation errors
             return response()->json([
                 'status' => false,
                 'message' => 'Validation Error',
                 'errors' => $e->errors(),
             ], 422);
         } catch (Exception $e) {
             
             // Return server error response
             return response()->json([
                 'status' => false,
                 'message' => 'An unexpected error occurred',
                 'error' => $e->getMessage(),
             ], 500);
         }
     }

    public function failedtowingdata(Request $request)
    {
        try {
            $validated = $request->validate([
                'asset_id' => 'required|integer',
                'user_id' => 'required|integer',
            ]);
            $towingRequest = TowingRequest::where('user_id', $validated['user_id'])
                ->where('asset_id', $validated['asset_id'])
                ->first();
            if ($towingRequest) {
                $towingRequest->update([
                    'failed_reason' => $request->input('failed_reason'),
                ]);
                DB::table('towings_requests')
                    ->where('asset_id', $validated['asset_id'])
                    ->where('user_id', $validated['user_id'])
                    ->update(['failed_towing' => 1]);
            } else {
                return response()->json([
                    'error' => 'No record found against Asset ID: ' . $validated['asset_id'] . ' and User ID: ' . $validated['user_id']
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Towing Failed Reason  Added successfully',
                'data' => $towingRequest,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function getUserPhone($asset_id = null)
    {

        if (!$asset_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asset ID is required.',
            ], 400);
        }


        $insuranceRecord = DB::table('insurance')
            ->where('asset_id', $asset_id)
            ->select('recovery_number', 'towingsavailable')
            ->first();

        if ($insuranceRecord) {
            return response()->json([
                'status' => 'success',
                'recovery_number' => $insuranceRecord->recovery_number,
                'towingsavailable' => $insuranceRecord->towingsavailable, // Include towingsavailable
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No record found for the provided asset_id',
            ], 404);
        }
    }

    public function getSingleUserData($user_id = null)
    {

        if (!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID is required.',
            ], 400);
        }


        $user_record = DB::table('towings_requests as tr')
            ->leftJoin('assets as a', 'tr.asset_id', '=', 'a.id')
            ->leftJoin('users as u', 'tr.user_id', '=', 'u.id')
            ->select('tr.*', 'a.asset_tag as asset_name', 'u.username as username')
            ->where('tr.user_id', $user_id)
            ->get();

        if ($user_record) {
            return response()->json([
                'status' => 'success',
                'user_record' => $user_record,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No record found for the provided user_id',
            ], 404);
        }
    }


    public function GetQuestionsLists()
    {
        $locations = BikeQuestion::all();
        return response()->json($locations);
    }
    public function GetLocationsLists()
    {
        $locations = Location::all();
        return response()->json($locations);
    }
    public function index(Request $request)
    {

        $this->authorize('view', Location::class);
        $allowed_columns = [
            'id',
            'name',
            'address',
            'address2',
            'city',
            'state',
            'country',
            'zip',
            'created_at',
            'updated_at',
            'manager_id',
            'image',
            'assigned_assets_count',
            'users_count',
            'assets_count',
            'assigned_assets_count',
            'assets_count',
            'rtd_assets_count',
            'currency',
            'ldap_ou',
        ];

        $locations = Location::with('parent', 'manager', 'children')->select([
            'locations.id',
            'locations.name',
            'locations.address',
            'locations.address2',
            'locations.city',
            'locations.state',
            'locations.zip',
            'locations.country',
            'locations.parent_id',
            'locations.manager_id',
            'locations.created_at',
            'locations.updated_at',
            'locations.image',
            'locations.ldap_ou',
            'locations.currency',
        ])->withCount('assignedAssets as assigned_assets_count')
            ->withCount('assets as assets_count')
            ->withCount('rtd_assets as rtd_assets_count')
            ->withCount('users as users_count');

        if ($request->filled('search')) {
            $locations = $locations->TextSearch($request->input('search'));
        }

        if ($request->filled('name')) {
            $locations->where('locations.name', '=', $request->input('name'));
        }

        if ($request->filled('address')) {
            $locations->where('locations.address', '=', $request->input('address'));
        }

        if ($request->filled('address2')) {
            $locations->where('locations.address2', '=', $request->input('address2'));
        }

        if ($request->filled('city')) {
            $locations->where('locations.city', '=', $request->input('city'));
        }

        if ($request->filled('zip')) {
            $locations->where('locations.zip', '=', $request->input('zip'));
        }

        if ($request->filled('country')) {
            $locations->where('locations.country', '=', $request->input('country'));
        }

        $offset = (($locations) && (request('offset') > $locations->count())) ? $locations->count() : request('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? $request->input('sort') : 'created_at';

        switch ($request->input('sort')) {
            case 'parent':
                $locations->OrderParent($order);
                break;
            case 'manager':
                $locations->OrderManager($order);
                break;
            default:
                $locations->orderBy($sort, $order);
                break;
        }


        $total = $locations->count();
        $locations = $locations->skip($offset)->take($limit)->get();

        return (new LocationsTransformer)->transformLocations($locations, $total);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  \App\Http\Requests\ImageUploadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageUploadRequest $request)
    {
        $this->authorize('create', Location::class);
        $location = new Location;
        $location->fill($request->all());
        $location = $request->handleImages($location);

        if ($location->save()) {
            return response()->json(Helper::formatStandardApiResponse('success', (new LocationsTransformer)->transformLocation($location), trans('admin/locations/message.create.success')));
        }

        return response()->json(Helper::formatStandardApiResponse('error', null, $location->getErrors()));
    }

    /**
     * Display the specified resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Location::class);
        $location = Location::with('parent', 'manager', 'children')
            ->select([
                'locations.id',
                'locations.name',
                'locations.address',
                'locations.address2',
                'locations.city',
                'locations.state',
                'locations.zip',
                'locations.country',
                'locations.parent_id',
                'locations.manager_id',
                'locations.created_at',
                'locations.updated_at',
                'locations.image',
                'locations.currency',
            ])
            ->withCount('assignedAssets as assigned_assets_count')
            ->withCount('assets as assets_count')
            ->withCount('rtd_assets as rtd_assets_count')
            ->withCount('users as users_count')
            ->findOrFail($id);

        return (new LocationsTransformer)->transformLocation($location);
    }


    /**
     * Update the specified resource in storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  \App\Http\Requests\ImageUploadRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ImageUploadRequest $request, $id)
    {
        $this->authorize('update', Location::class);
        $location = Location::findOrFail($id);

        $location->fill($request->all());
        $location = $request->handleImages($location);

        if ($location->isValid()) {

            $location->save();
            return response()->json(
                Helper::formatStandardApiResponse(
                    'success',
                    (new LocationsTransformer)->transformLocation($location),
                    trans('admin/locations/message.update.success')
                )
            );
        }

        return response()->json(Helper::formatStandardApiResponse('error', null, $location->getErrors()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Location::class);
        $location = Location::findOrFail($id);
        if (!$location->isDeletable()) {
            return response()
                ->json(Helper::formatStandardApiResponse('error', null, trans('admin/companies/message.assoc_users')));
        }
        $this->authorize('delete', $location);
        $location->delete();

        return response()->json(Helper::formatStandardApiResponse('success', null, trans('admin/locations/message.delete.success')));
    }

    /**
     * Gets a paginated collection for the select2 menus
     *
     * This is handled slightly differently as of ~4.7.8-pre, as
     * we have to do some recursive magic to get the hierarchy to display
     * properly when looking at the parent/child relationship in the
     * rich menus.
     *
     * This means we can't use the normal pagination that we use elsewhere
     * in our selectlists, since we have to get the full set before we can
     * determine which location is parent/child/grandchild, etc.
     *
     * This also means that hierarchy display gets a little funky when people
     * use the Select2 search functionality, but there's not much we can do about
     * that right now.
     *
     * As a result, instead of paginating as part of the query, we have to grab
     * the entire data set, and then invoke a paginator manually and pass that
     * through to the SelectListTransformer.
     *
     * Many thanks to @uberbrady for the help getting this working better.
     * Recursion still sucks, but I guess he doesn't have to get in the
     * sea... this time.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0.16]
     * @see \App\Http\Transformers\SelectlistTransformer
     */
    public function selectlist(Request $request)
    {

        $this->authorize('view.selectlists');

        $locations = Location::select([
            'locations.id',
            'locations.name',
            'locations.parent_id',
            'locations.image',
        ]);

        $page = 1;
        if ($request->filled('page')) {
            $page = $request->input('page');
        }

        if ($request->filled('search')) {
            $locations = $locations->where('locations.name', 'LIKE', '%' . $request->input('search') . '%');
        }

        $locations = $locations->orderBy('name', 'ASC')->get();

        $locations_with_children = [];

        foreach ($locations as $location) {
            if (!array_key_exists($location->parent_id, $locations_with_children)) {
                $locations_with_children[$location->parent_id] = [];
            }
            $locations_with_children[$location->parent_id][] = $location;
        }

        if ($request->filled('search')) {
            $locations_formatted = $locations;
        } else {
            $location_options = Location::indenter($locations_with_children);
            $locations_formatted = new Collection($location_options);
        }

        $paginated_results = new LengthAwarePaginator($locations_formatted->forPage($page, 500), $locations_formatted->count(), 500, $page, []);

        //return [];
        return (new SelectlistTransformer)->transformSelectlist($paginated_results);
    }
}
