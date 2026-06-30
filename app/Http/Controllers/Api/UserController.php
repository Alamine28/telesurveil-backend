<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['success'=>true,'data'=>User::with('departement')->paginate(15)]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'prenom'   => 'required|string|max:100',
            'nom'      => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:administrateur,chef_scolarite,superviseur',
            'departement_id' => 'required|exists:departements,id',
        ]);
        $data['password'] = Hash::make($data['password']);
        return response()->json(['success'=>true,'data'=>User::create($data)->load('departement')], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json(['success'=>true,'data'=>$user->load('departement')]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'prenom'   => 'sometimes|string|max:100',
            'nom'      => 'sometimes|string|max:100',
            'email'    => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'role'     => 'sometimes|in:administrateur,chef_scolarite,superviseur',
            'departement_id' => 'sometimes|exists:departements,id',
        ]);
        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        $user->update($data);
        return response()->json(['success'=>true,'data'=>$user->load('departement')]);
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success'=>false,'message'=>'Impossible de supprimer votre propre compte.'], 403);
        }
        $user->delete();
        return response()->json(['success'=>true,'message'=>'Utilisateur supprimé.']);
    }
}
