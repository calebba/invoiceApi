<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
        {
            return response()->json(['message' => 'Registeration failed', 'error'=>$validator->errors()->all()], 400);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        $token = $user->createToken('AuthToken')->accessToken;

        return response()->json(['message' => 'Registeration Successful','token' => $token], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $token = auth()->user()->createToken('AuthToken')->accessToken;
            $user = auth()->user();
            return response()->json(['message' => 'Login Successful', 'token' => $token, 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Login failed','error' => 'Failed to authenticated'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
