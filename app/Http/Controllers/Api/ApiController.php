<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;




class ApiController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|numeric|digits:10',
            ]);

            Log::info('User creation request data:', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            Log::info('Created User:', $user->toArray());

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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);


        $user = User::where('email', $request->email)->first();


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
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'sometimes|required|string|max:15',
        ]);


        $user = User::find($id);

        if ($user) {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;


            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                "status" => true,
                "message" => "User updated successfully",
                "data" => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
    }
    public function show()
    {
        $user = Auth::user();

        return response()->json([
            "status" => true,
            "message" => "User profile viewed Successfully",
            "data" => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully from all devices',
        ], 200);
    }
}
