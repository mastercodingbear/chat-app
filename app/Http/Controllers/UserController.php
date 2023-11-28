<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:users',
        ]);

        $token = Str::random(60);

        $user = new User;

        $user->name = $request->name;
        $user->token = $token;

        $user->save();

        return response()->json(['token' => $token]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = User::where('token', $request->token)->first();

        if ($user) {
            // Generate API token
            $token = $user->createToken($user->token);

            return response()->json(['token' => $token->plainTextToken]);
        } else {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }
}
