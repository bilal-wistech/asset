<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Fine;
use App\Models\Location;
use App\Models\FineType;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendPushNotification;
use Kutia\Larafirebase\Facades\Larafirebase;
use ExpoSDK\Expo;
use App\Models\ExpoToken;
use ExpoSDK\ExpoMessage;
use Image;

class FineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Fine::class);
        return view('fines.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $this->authorize('create', Fine::class);
    //     $assets = Asset::all()->pluck('asset_tag', 'id')->toArray();
    //     $users = User::all()->pluck('username', 'id')->toArray();
    //     $fine_type = FineType::all()->pluck('name', 'id')->toArray();
    //     $location = Location::all()->pluck('name', 'id')->toArray();
    //     // dd($assets);
    //     return view('fines.edit', compact('assets', 'fine_type', 'location', 'users'));
    // }
    public function create()
    {
        $this->authorize('create', Fine::class);
        // Fetch the full collection of assets instead of plucking
        $assets = Asset::all();
        $users = User::all()->pluck('username', 'id')->toArray();
        $fine_type = FineType::all()->pluck('name', 'id')->toArray();
        $location = Location::all()->pluck('name', 'id')->toArray();

        return view('fines.edit', compact('assets', 'fine_type', 'location', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //dd($request->all());
    //     $data = $request->all();
    //     // dd($request->fine_type->fine);
    //     if ($request->file('fine_image')) {
    //         $image = $request->file('fine_image');
    //         //  dd($image);
    //         $imageName = time() . '.' . $image->getClientOriginalExtension();


    //         $image_resize = Image::make($image->getRealPath());

    //         $image_resize->resize(1000, 1000);
    //         // dd($image_resize);
    //         $path = 'uploads/fines/' . $imageName;

    //         $image_resize->save($path);

    //         $imageUri = 'uploads/fines/' . $imageName;

    //         $data['fine_image'] = $path;
    //     }

    //     $fine = Fine::create($data);

    //     $detail = Fine::latest()->first();
    //     $detail->update(['notification' => 1]);
    //     //   dd($detail->user_id);

    //     $type_name = $detail->type && $detail->type->name != null ? $detail->type->name : 'not available';
    //     $location_name = $detail->findLocation && $detail->findLocation->name != null ? $detail->findLocation->name : 'not available';
    //     $asset_name = $detail->asset && $detail->asset->asset_tag != null ? $detail->asset->asset_tag : 'not available';
    //     $user_name = $detail->user && $detail->user->username != null ? $detail->user->username : 'not available';
    //     $appUrl = config('app.url');

    //     //   $imageUrl = $appUrl . $detail->fine_image 
    //     $imageUrl = url(e($detail->fine_image));
    //     //   dd($imageUrl);
    //     //=================== latest for notification ====================

    //     try {

    //         $tokens = ExpoToken::where('user_id', $detail->user_id)->pluck('expo_token')->toArray();
    //         // dd($tokens);
    //         foreach ($tokens as $token) {
    //             //   dd($token);
    //             $messages =
    //                 new ExpoMessage([
    //                     'title' => 'Fine notification',
    //                     'body' => 'You have got a Fine',
    //                     'data' => [
    //                         'user_id' => $user_name,
    //                         'amount' => $request->amount,
    //                         'fine_date' => $request->fine_date,
    //                         'fine_type' => $type_name,
    //                         'asset_id' => $asset_name,
    //                         'location' => $location_name,
    //                         'note' => $request->note,
    //                         'image' => $imageUrl,

    //                     ],

    //                 ]);

    //             (new Expo)->send($messages)->to($token)->push();
    //         }

    //         return redirect()->route('fines')->with('success', 'Fine created successfully!');
    //     } catch (\Exception $e) {
    //         report($e);
    //         return redirect()->back()->with('error', 'Something goes wrong while sending notification.');
    //     }
    // }
    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('fine_image')) {
            $imagePaths = [];  // Array to store paths of the uploaded images

            foreach ($request->file('fine_image') as $image) {
                // Generate unique name for each image
                $imageName = time() . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(1000, 1000);  // Resize image
                $path = 'uploads/fines/' . $imageName;
                $image_resize->save($path);

                // Store each image path in the array
                $imagePaths[] = $path;
            }

            // Save the images as a comma-separated string in the database
            $data['fine_image'] = implode(',', $imagePaths);
        }

        // Create Fine record
        $fine = Fine::create($data);

        // Get the latest Fine record
        $detail = Fine::latest()->first();
        $detail->update(['notification' => 1]);

        // Get additional details for notification
        $type_name = $detail->type && $detail->type->name ? $detail->type->name : 'not available';
        $location_name = $detail->findLocation && $detail->findLocation->name ? $detail->findLocation->name : 'not available';
        $asset_name = $detail->asset && $detail->asset->asset_tag ? $detail->asset->asset_tag : 'not available';
        $user_name = $detail->user && $detail->user->username ? $detail->user->username : 'not available';
        $appUrl = config('app.url');

        // Generate URLs for all fine images
        $imageUrls = array_map(function ($imagePath) use ($appUrl) {
            return url($imagePath);
        }, explode(',', $detail->fine_image));
        // dd($imageUrls);
        // Send notifications
        try {
            $tokens = ExpoToken::where('user_id', $detail->user_id)->pluck('expo_token')->toArray();
            foreach ($tokens as $token) {
                $messages = new ExpoMessage([
                    'title' => 'Fine notification',
                    'body' => 'You have got a Fine',
                    'data' => [
                        'user_id' => $user_name,
                        'amount' => $request->amount,
                        'fine_date' => $request->fine_date,
                        'fine_type' => $type_name,
                        'asset_id' => $asset_name,
                        'location' => $location_name,
                        'note' => $request->note,
                        'image' => $imageUrls,
                    ],
                ]);

                (new Expo)->send($messages)->to($token)->push();
            }

            return redirect()->route('fines')->with('success', 'Fine created successfully!');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Something goes wrong while sending notification.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Fine::find($id);
        dd($data->type->name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Fine::class);
        $fine = Fine::find($id);

        $assets = Asset::all();
        $users = User::all()->pluck('username', 'id')->toArray();
        $fine_type = FineType::all()->pluck('name', 'id')->toArray();
        $location = Location::all()->pluck('name', 'id')->toArray();
        return view('fines.edit', compact('fine', 'assets', 'users', 'fine_type', 'location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {

    //     //dd($request->all());
    //     // $model = Fine::find($id);
    //     $user = Fine::find($id);

    //     // Check if the user exists
    //     if ($user) {

    //         $user->fine_type = $request->fine_type;
    //         $user->fine_number = $request->fine_number;
    //         $user->amount = $request->amount;
    //         $user->user_id = $request->user_id;
    //         $user->asset_id = $request->asset_id;
    //         $user->location = $request->location;
    //         $user->fine_date = $request->fine_date;
    //         $user->recieved_by_user = $request->recieved_by_user;
    //         $user->note = $request->note;
    //         $user->save();
    //         return redirect()->route('fines')->with('success', 'data updated successfully');
    //     }
    //     ;
    // }
    public function update(Request $request, $id)
    {
        // Find the existing fine record by ID
        $user = Fine::find($id);

        // Check if the user (fine record) exists
        if ($user) {
            // Update the basic fine details
            $user->fine_type = $request->fine_type;
            $user->fine_number = $request->fine_number;
            $user->amount = $request->amount;
            $user->user_id = $request->user_id;
            $user->asset_id = $request->asset_id;
            $user->location = $request->location;
            $user->fine_date = $request->fine_date;
            $user->recieved_by_user = $request->recieved_by_user;
            $user->note = $request->note;

            // Handle the fine image uploads if new images are uploaded
            if ($request->hasFile('fine_image')) {
                // Initialize an empty array to hold the new image paths
                $imagePaths = [];

                // If the user already has images, get the existing ones
                if ($user->fine_image) {
                    // Split the comma-separated string of existing images
                    $imagePaths = explode(',', $user->fine_image);
                }

                // Loop through each uploaded image and process them
                foreach ($request->file('fine_image') as $image) {
                    $imageName = time() . rand(100, 999) . '.' . $image->getClientOriginalExtension();
                    $image_resize = Image::make($image->getRealPath());

                    // Resize the image to a fixed size
                    $image_resize->resize(1000, 1000);

                    // Save the image to the uploads directory
                    $path = 'uploads/fines/' . $imageName;
                    $image_resize->save(public_path($path));

                    // Add the image path to the array
                    $imagePaths[] = $path;
                }

                // Join the array of image paths into a comma-separated string
                $user->fine_image = implode(',', $imagePaths);
            }

            // Save the updated fine record
            $user->save();

            // Redirect with success message
            return redirect()->route('fines')->with('success', 'Data updated successfully');
        }

        // Redirect with error message if fine record not found
        return redirect()->back()->with('error', 'Fine record not found');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Fine::class);
        $data = Fine::find($id);
        $data->delete();
        return redirect()->route('fines')->with('success', 'data deleted successfully');
    }
    public function getFineTypeAmount(Request $request)
    {
        $fineTypeId = $request->fine_type_id;
        $fineType = FineType::find($fineTypeId);
        if ($fineType) {
            return response()->json(['amount' => $fineType->amount]);
        } else {
            return response()->json(['amount' => null]);
        }
    }

    public function fetchFines(Request $request)
    {
        $Date = $request->input('fine_date');
        $assetId = $request->input('asset_id');
        // $fineDate = Carbon::parse($Date)->format('Y-m-d H:i:s');
        // $asset = Asset::where('last_checkout', $fineDate)
        //     ->where('id', $assetId)
        //     ->first();

        $fineDate = Carbon::parse($Date)->format('Y-m-d H:i');

        // Query to match the `last_checkout` ignoring seconds
        $asset = Asset::where('id', $assetId)
            ->whereRaw("DATE_FORMAT(last_checkout, '%Y-%m-%d %H:%i') = ?", [$fineDate])
            ->first();
        if ($asset) {
            $userId = $asset->user_id;
            $user = User::where('id', $userId)
                ->select('id', 'username')
                ->first();
            return response()->json([
                'success' => true,
                'message' => $user
            ]);
        } else {
            $users = User::all()->pluck('username', 'id')->toArray();
            return response()->json([

                'success' => false,
                'message' => 'There is no user for Selected datetime.',
                'users' => $users
            ]);
        }
    }

}
