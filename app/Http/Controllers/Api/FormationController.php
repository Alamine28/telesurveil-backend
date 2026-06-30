<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Formation::with(['departement', 'ecs'])->paginate(15),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom_formation' => 'required|string|max:255',
            'responsable_formation' => 'required|string|max:255',
            'cycle' => 'required|in:licence,master,doctorat',
            'departement_id' => 'required|exists:departements,id',
        ]);

        return response()->json([
            'success' => true,
            'data' => Formation::create($data)->load('departement'),
        ], 201);
    }

    public function show(Formation $formation): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $formation->load(['departement', 'ecs']),
        ]);
    }

    public function update(Request $request, Formation $formation): JsonResponse
    {
        $data = $request->validate([
            'nom_formation' => 'sometimes|string|max:255',
            'responsable_formation' => 'sometimes|string|max:255',
            'cycle' => 'sometimes|in:licence,master,doctorat',
            'departement_id' => 'sometimes|exists:departements,id',
        ]);

        $formation->update($data);

        return response()->json([
            'success' => true,
            'data' => $formation->load('departement'),
        ]);
    }

    public function destroy(Formation $formation): JsonResponse
    {
        $formation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Formation supprimee.',
        ]);
    }
}
