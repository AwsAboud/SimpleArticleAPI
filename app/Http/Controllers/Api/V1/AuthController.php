<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $device = substr($request->userAgent() ?? '', 0, 255 );
        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $user->createToken($device)->plainTextToken
        ]);
    }

    public function login(LoginRequest $request){
        if(! Auth::attempt($request->only('email','password')) ){
            return response()->json([
                        'error' => 'The provided credintials are incorrect.',
                    ], 401);
        }
        $user = User::where('email', $request->email)->first();
        $device = substr($request->userAgent() ?? '', 0, 255 );
        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $user->createToken($device)->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        //delete all user's tokens
        //$request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
