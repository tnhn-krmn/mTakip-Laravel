<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => md5($request->password)
        ]);

        $user = $user->save();

        $credentailes = ['email' => $request->email, 'password' => $request->password];

        if (!Auth::attempt($credentailes)) {
            return response()->json([
                'message' => 'Giriş Yapılamadı'
            ], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'success' => true,
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentailes = request(['email','password']);
        
        if (!Auth::attempt($credentailes)) {
            return response()->json([
                'message' => 'Bilgiler Hatalı'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access');
        $token = $tokenResult->token;
        $token->save();  
        
        return response()->json([
            'success' => true,
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Çıkış Yapıldı'
        ], 201);
    }



    public function authenticate(Request $request)
    {
        $user = [];
        if (Auth::check()) 
        {
           $user = $request->user();
        }

        return response()->json([
            'user' => $user,
            'isLoggedIn' => Auth::check()
        ], 201);
    }
}
