<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'name' => 'required|string|max:255',
                'emailid' => 'required|email|unique:users,emailid',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:15',
            ]);

            // Log the incoming request data
            Log::info('User creation request data:', [
                'name' => $request->name,
                'emailid' => $request->emailid,
                'phone' => $request->phone
            ]);

            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'emailid' => $request->emailid,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            // Log the created user data
            Log::info('Created User:', $user->toArray());

            // Return the response
            return response()->json([
                "status" => true,
                "message" => "User created Successfully",
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                "data" => $user,
            ], 200);
        } catch (\Exception $th) {
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
                'message' => 'Invalid',
            ], 401);
        }
    }
    public function updateUser(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'emailid' => 'sometimes|required|email|unique:users,emailid,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'sometimes|required|string|max:15',
        ]);

        // Find the user
        $user = User::find($id);

        if ($user) {
            // Update user details
            $user->name = $request->name;
            $user->emailid = $request->emailid;
            $user->phone = $request->phone;

            // Check if password is filled, then hash and update it
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Save the changes
            $user->save();

            // Return success response
            return response()->json([
                "status" => true,
                "message" => "User updated successfully",
                "data" => $user,
            ], 200);
        } else {
            // Return 404 response if user is not found
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
    }

    public function logout(Request $request)
    {
        // Revoke all tokens  for the user
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully from all devices',
        ], 200);
    }
}
