<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $result = $this->authService->login($credentials, $request->ip());
            return response()->json([
                'success' => true,
                'data'    => ['user' => $result['user'], 'token' => $result['token']],
                'message' => 'Connexion réussie.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Identifiants invalides.'], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user(), $request->ip());
        return response()->json(['success' => true, 'message' => 'Déconnexion réussie.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $request->user()]);
    }
}