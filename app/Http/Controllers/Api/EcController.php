<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ec;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EcController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Ec::with('formation.departement')->paginate(15),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code_ec' => 'required|string|max:100|unique:ecs,code_ec',
            'libelle' => 'required|string|max:255',
            'semestre' => 'required|in:S1,S2,S3,S4,S5,S6',
            'niveau' => 'required|in:L1,L2,L3,M1,M2,LPCM',
            'annee_academique' => 'required|string|max:20',
            'formation_id' => 'required|exists:formations,id',
        ]);

        return response()->json([
            'success' => true,
            'data' => Ec::create($data)->load('formation.departement'),
        ], 201);
    }

    public function show(Ec $ec): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $ec->load(['formation.departement', 'evaluations']),
        ]);
    }

    public function update(Request $request, Ec $ec): JsonResponse
    {
        $data = $request->validate([
            'code_ec' => 'sometimes|string|max:100|unique:ecs,code_ec,'.$ec->id,
            'libelle' => 'sometimes|string|max:255',
            'semestre' => 'sometimes|in:S1,S2,S3,S4,S5,S6',
            'niveau' => 'sometimes|in:L1,L2,L3,M1,M2,LPCM',
            'annee_academique' => 'sometimes|string|max:20',
            'formation_id' => 'sometimes|exists:formations,id',
        ]);

        $ec->update($data);

        return response()->json([
            'success' => true,
            'data' => $ec->load('formation.departement'),
        ]);
    }

    public function destroy(Ec $ec): JsonResponse
    {
        $ec->delete();

        return response()->json([
            'success' => true,
            'message' => 'EC supprime.',
        ]);
    }
}
