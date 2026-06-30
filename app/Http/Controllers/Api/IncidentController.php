<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Services\IncidentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function __construct(private IncidentService $incidentService) {}

    public function index(Request $request): JsonResponse
    {
        $query = Incident::with(['evaluationSalle.salle','declarant']);
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('evaluation_salle_id')) $query->where('evaluation_salle_id', $request->evaluation_salle_id);
        return response()->json(['success'=>true,'data'=>$query->latest()->paginate(20)]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'categorie'           => 'required|string|max:100',
            'description'         => 'required|string',
            'evaluation_salle_id' => 'required|exists:evaluation_salle,id',
            'videos'              => 'sometimes|array',
            'videos.*.video_id'   => 'exists:videos,id',
            'videos.*.timestamp'  => 'nullable|integer|min:0',
        ]);

        $incident = $this->incidentService->declarer($data, $request->user()->id, $request->ip());
        return response()->json(['success'=>true,'data'=>$incident], 201);
    }

    public function show(Incident $incident): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $incident->load(['evaluationSalle.salle','declarant','videos','rapports']),
        ]);
    }

    public function changerStatut(Request $request, Incident $incident): JsonResponse
    {
        $request->validate(['statut'=>'required|in:en_cours,confirme,rejette']);
        try {
            $updated = $this->incidentService->changerStatut($incident, $request->statut, $request->user()->id, $request->ip());
            return response()->json(['success'=>true,'data'=>$updated]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 422);
        }
    }

    public function destroy(Incident $incident): JsonResponse
    {
        $incident->delete();
        return response()->json(['success'=>true,'message'=>'Incident supprimé.']);
    }
}