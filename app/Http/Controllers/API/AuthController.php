<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Incorrect credentials. Please try again'],
            ]);
        }

        $token = $user->createToken('news-aggregator')->plainTextToken;

        return response()->json(['message' => 'Successfully logged in!' ,'token' => $token], 200);
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPasword' => 'requried'
        ]);

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->newPassword)
        ]);

        $request->user()->tokens()->delete();

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully updated the user password!'], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'You have successfully logged out!'], 200);
    }
}
