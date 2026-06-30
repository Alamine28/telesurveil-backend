<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surveillant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveillantController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Surveillant::paginate(15),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom_surveillant' => 'required|string|max:100',
            'prenom_surveillant' => 'required|string|max:100',
            'adresse' => 'nullable|string|max:255',
            'email_surveillant' => 'required|email|unique:surveillants,email_surveillant',
            'telephone' => 'nullable|string|max:20',
            'profession' => 'nullable|string|max:100',
        ]);

        return response()->json([
            'success' => true,
            'data' => Surveillant::create($data),
        ], 201);
    }

    public function show(Surveillant $surveillant): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $surveillant->load('evaluationSalles.evaluation', 'evaluationSalles.salle'),
        ]);
    }

    public function update(Request $request, Surveillant $surveillant): JsonResponse
    {
        $data = $request->validate([
            'nom_surveillant' => 'sometimes|string|max:100',
            'prenom_surveillant' => 'sometimes|string|max:100',
            'adresse' => 'nullable|string|max:255',
            'email_surveillant' => 'sometimes|email|unique:surveillants,email_surveillant,'.$surveillant->id,
            'telephone' => 'nullable|string|max:20',
            'profession' => 'nullable|string|max:100',
        ]);

        $surveillant->update($data);

        return response()->json([
            'success' => true,
            'data' => $surveillant,
        ]);
    }

    public function destroy(Surveillant $surveillant): JsonResponse
    {
        $surveillant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surveillant supprime.',
        ]);
    }
}
