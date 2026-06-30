<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationSalle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function index(): JsonResponse
    {
        $evals = Evaluation::with(['ec.formation','salles','evaluationSalles.surveillants'])->paginate(15);
        return response()->json(['success'=>true,'data'=>$evals]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'            => 'required|in:examen_session1,examen_session2,devoir',
            'date_evaluation' => 'required|date|after_or_equal:today',
            'heure_debut'     => 'required|date_format:H:i',
            'heure_fin'       => 'required|date_format:H:i|after:heure_debut',
            'ec_id'           => 'required|exists:ecs,id',
            'salles'          => 'required|array|min:1',
            'salles.*.id'                => 'required|exists:salles,id',
            'salles.*.surveillant_ids'   => 'sometimes|array',
            'salles.*.surveillant_ids.*' => 'exists:surveillants,id',
        ]);

        return DB::transaction(function () use ($data, $request) {
            foreach ($data['salles'] as $salleData) {
                $conflit = EvaluationSalle::whereHas('evaluation', function ($q) use ($data) {
                    $q->where('date_evaluation', $data['date_evaluation'])
                      ->whereBetween('heure_debut', [$data['heure_debut'], $data['heure_fin']]);
                })->where('salle_id', $salleData['id'])->exists();

                if ($conflit) {
                    return response()->json([
                        'success' => false,
                        'message' => "Conflit : la salle #{$salleData['id']} est déjà occupée sur ce créneau.",
                    ], 422);
                }
            }

            $evaluation = Evaluation::create([
                'type'            => $data['type'],
                'date_evaluation' => $data['date_evaluation'],
                'heure_debut'     => $data['heure_debut'],
                'heure_fin'       => $data['heure_fin'],
                'ec_id'           => $data['ec_id'],
            ]);

            foreach ($data['salles'] as $salleData) {
                $evSalle = EvaluationSalle::create([
                    'evaluation_id' => $evaluation->id,
                    'salle_id'      => $salleData['id'],
                    'timestamp'     => now()->toIso8601String(),
                ]);

                if (!empty($salleData['surveillant_ids'])) {
                    $evSalle->surveillants()->sync($salleData['surveillant_ids']);
                }
            }

            return response()->json([
                'success' => true,
                'data'    => $evaluation->load('ec','salles','evaluationSalles.surveillants'),
            ], 201);
        });
    }

    public function show(Evaluation $evaluation): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $evaluation->load('ec.formation','salles','evaluationSalles.surveillants','evaluationSalles.incidents'),
        ]);
    }

    public function destroy(Evaluation $evaluation): JsonResponse
    {
        $evaluation->delete();
        return response()->json(['success'=>true,'message'=>'Évaluation supprimée.']);
    }
}