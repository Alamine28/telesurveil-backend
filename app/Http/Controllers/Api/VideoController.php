<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Video::with(['camera.salle', 'evaluationSalle.evaluation'])->paginate(15),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'date_enreg' => 'required|date',
            'chemin_fichier' => 'required|string|max:255',
            'camera_id' => 'required|exists:cameras,id',
            'evaluation_salle_id' => 'nullable|exists:evaluation_salle,id',
        ]);

        return response()->json([
            'success' => true,
            'data' => Video::create($data)->load(['camera.salle', 'evaluationSalle.evaluation']),
        ], 201);
    }

    public function show(Video $video): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $video->load(['camera.salle', 'evaluationSalle.evaluation', 'incidents']),
        ]);
    }

    public function update(Request $request, Video $video): JsonResponse
    {
        $data = $request->validate([
            'date_enreg' => 'sometimes|date',
            'chemin_fichier' => 'sometimes|string|max:255',
            'camera_id' => 'sometimes|exists:cameras,id',
            'evaluation_salle_id' => 'nullable|exists:evaluation_salle,id',
        ]);

        $video->update($data);

        return response()->json([
            'success' => true,
            'data' => $video->load(['camera.salle', 'evaluationSalle.evaluation']),
        ]);
    }

    public function destroy(Video $video): JsonResponse
    {
        $video->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video supprimee.',
        ]);
    }
}
