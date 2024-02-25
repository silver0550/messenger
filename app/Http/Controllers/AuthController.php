<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(AuthRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            $user = Auth::user();
            $user->tokens()->where('name', $credentials['device_name'])->delete(); //TODO: a felhasználóra vonatkozzon

            $token = $user->createToken($credentials['device_name'])->accessToken;

            return response()->json(['user' => new UserResource($user), 'access_token' => $token], 200);
        }

        return response()->json(['massage' => __('auth.unauthorized')], 401);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['user' => $user], 201);
    }
}
