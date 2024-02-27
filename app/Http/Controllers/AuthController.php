<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function login(AuthRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            $user = Auth::user();

            if ($user->status == StatusEnum::INACTIVE) {

                Auth::logout();

                return response()->json(
                    ['message' => __('json_message.status', [
                        'attribute' => StatusEnum::INACTIVE->getReadableText()
                        ])
                    ],ResponseCode::HTTP_FORBIDDEN
                );
            }

            $token = $user->createToken($credentials['device_name'])->plainTextToken;

            return response()->json(
                ['access_token' => $token],
                ResponseCode::HTTP_OK
            );
        }

        return response()->json(
            ['message' => __('auth.failed')],
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();

        $user->tokens()->delete();

        Auth::logout();

        return response()->json(
            ['message' => __('auth.logged_out_success')],
            ResponseCode::HTTP_OK
        );
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->authService->createUser($validated);

        return response()->json(
            ['user' => new UserResource($user)],
            ResponseCode::HTTP_CREATED);
    }
}
