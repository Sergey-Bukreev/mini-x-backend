<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Messages\AuthMessages;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\AuthTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json([
            'message' => __(AuthMessages::Registered->value),
            'user' => $user,
        ], 201);
    }

    public function login(Request $request, AuthTokenService $tokenService) {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => __(AuthMessages::InvalidCredentials->value),
            ], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'message' => __(AuthMessages::InvalidCredentials->value),
            ], 401);
        }

        $refreshToken = $tokenService->generateRefreshToken();

        $tokenService->createRefreshSession($user, $refreshToken, $request);
        $accessToken = $tokenService->createAccessToken($user);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => $user,
        ]);
    }

    public function refresh(Request $request, AuthTokenService $tokenService) {
        $refreshToken = $request->input('refresh_token');

        $authSession = $tokenService->findSessionByRefreshToken($refreshToken);

        if (
            !$authSession ||
            $authSession->revoked_at !== null ||
            $authSession->expires_at < now() ||
            $authSession->absolute_expires_at < now()
        ) {
            return response()->json([
                'message' => __(AuthMessages::InvalidSession->value),
            ], 401);
        }

        $user = User::find($authSession->user_id);

        if (!$user) {
            return response()->json([
                'message' => __(AuthMessages::UserNotFound->value),
            ], 404);
        }

        $newRefreshToken = $tokenService->generateRefreshToken();

        $tokenService->rotateRefreshSession($authSession, $user, $newRefreshToken, $request);

        $accessToken = $tokenService->createAccessToken($user);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'user' => $user,
        ]);

    }


}
