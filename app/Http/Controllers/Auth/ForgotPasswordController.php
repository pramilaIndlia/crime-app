<?php

// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// // use Illuminate\Auth\Events\Validated;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Password;
// use Illuminate\Support\Facades\URL;
// use Illuminate\Support\Facades\Validator;


// class ForgotPasswordController extends Controller
// {
//     public function create()
//     {
//         return view('auth.forgot-password'); // Return the forgot-password view
//     }

//     public function store(Request $request)
//     {
//         // Validate email
//         $request->validate(['email' => 'required|email']);

//         // Send the password reset link
//         $status = Password::sendResetLink(
//             $request->only('email')
//         );

//         // Check if the status is successful and respond accordingly
//         return $status === Password::RESET_LINK_SENT
//             ? back()->with(['status' => __($status)])
//             : back()->withErrors(['email' => __($status)]);
//     }
// }


// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Password;

// class ForgotPasswordController extends Controller
// {
//     public function sendResetLinkEmail(Request $request)
//     {
//         $request->validate(['email' => 'required|email']);

//         // Send the password reset link to the email
//         $status = Password::sendResetLink(
//             $request->only('email')
//         );

//         // Respond with success or error message
//         return $status === Password::RESET_LINK_SENT
//             ? response()->json(['message' => __($status)], 200)
//             : response()->json(['message' => __($status)], 400);
//     }
// }



namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email.'], 200)
            : response()->json(['error' => 'Unable to send reset link'], 500);
    }
}
