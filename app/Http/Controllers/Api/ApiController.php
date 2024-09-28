<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        try {
            //  validation
            $request->validate([
                "name" => 'required|string|max:255',
                'emailid' => 'required|email|unique:users,emailid',
                "password" => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:15',

            ]);

            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'emailid' => $request->emailid,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                "status" => true,
                "message" => "User created Successfully",
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                "data" => $user,

            ], 200);
        } catch (Error $th) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }


    public function login(Request $request)
    {
        // Validate login credentials
        $request->validate([
            'emailid' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Check if the user exists
        $user = User::where('emailid', $request->emailid)->first();


        if ($user && Hash::check($request->password, $user->password)) {

            $token = $user->createToken('authToken')->plainTextToken;


            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,

            ], 200);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }
    }
}
