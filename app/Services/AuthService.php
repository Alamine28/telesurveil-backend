<?php

namespace App\Services;

use App\Models\User;
use App\Models\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials, string $ip): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        Log::create([
            'action'       => "Connexion de {$user->email}",
            'date_action'  => now()->toDateString(),
            'heure_action' => now()->toTimeString(),
            'user_id'      => $user->id,
            'ip_address'   => $ip,
        ]);

        return ['user' => $user, 'token' => $token];
    }

    public function logout(User $user, string $ip): void
    {
        $user->currentAccessToken()->delete();

        Log::create([
            'action'       => "Déconnexion de {$user->email}",
            'date_action'  => now()->toDateString(),
            'heure_action' => now()->toTimeString(),
            'user_id'      => $user->id,
            'ip_address'   => $ip,
        ]);
    }
}