<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpoToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request)
    {
        //  return  'hello';
        // return $request->expoPushToken;
        $credentials = $request->only('username', 'password', 'expoPushToken');

        $user = User::where('username', $request->username)->first();
       
        //  return $user;
        if ($user) {
            //$data = json_decode($user->permissions, true);
            // $superuser = $data['superuser'];
            // $admin = $data['admin'];
            
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('token')->accessToken;

                    //   $device_id = $request->expoPushToken != null ?  User::where('id', $user->id)->update([
                    //         'fcm_token'=>$request->expoPushToken

                    //     ]): null;

                    if ($request->expoPushToken !== null) {
                        $expoToken = new ExpoToken([
                            'expo_token' => $request->expoPushToken,
                            'user_id' => $user->id,
                        ]);

                        $expoToken->save();
                        // $device_id = $expoToken->id; // Assuming 'id' is your primary key in the ExpoToken table
                    }


                    $response = [
                        'username' => $user->username,
                        'user id' => $user->id,
                        'token' => $token,
                        'device_id' => $request->expoPushToken,
                    ];
                    return response($response, 200);
                } else {
                    $response = ["message" => "Password mismatch"];
                    return response($response, 422);
                }
            
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();
        // return 'logged out'; 

        return response()->json(['message' => 'Successfully logged out']);
    }
}
