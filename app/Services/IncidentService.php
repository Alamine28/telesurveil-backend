<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\Log;
use Illuminate\Support\Facades\DB;

class IncidentService
{
    public function declarer(array $data, int $userId, string $ip): Incident
    {
        return DB::transaction(function () use ($data, $userId, $ip) {
            $incident = Incident::create([
                'categorie'           => $data['categorie'],
                'description'         => $data['description'],
                'statut'              => 'declare',
                'date_incident'       => now()->toDateString(),
                'heure_incident'      => now()->toTimeString(),
                'evaluation_salle_id' => $data['evaluation_salle_id'],
                'user_id'             => $userId,
            ]);

            if (!empty($data['videos'])) {
                foreach ($data['videos'] as $v) {
                    $incident->videos()->attach($v['video_id'], ['timestamp' => $v['timestamp'] ?? null]);
                }
            }

            Log::create([
                'action'       => "Incident #{$incident->id} déclaré",
                'date_action'  => now()->toDateString(),
                'heure_action' => now()->toTimeString(),
                'user_id'      => $userId,
                'ip_address'   => $ip,
            ]);

            return $incident->load(['evaluationSalle.salle','declarant','videos']);
        });
    }

    public function changerStatut(Incident $incident, string $nouveauStatut, int $userId, string $ip): Incident
    {
        $transitions = [
            'declare'  => ['en_cours'],
            'en_cours' => ['confirme', 'rejette'],
            'confirme' => [],
            'rejette'  => [],
        ];

        if (! in_array($nouveauStatut, $transitions[$incident->statut] ?? [])) {
            throw new \InvalidArgumentException(
                "Transition '{$incident->statut}' → '{$nouveauStatut}' non autorisée."
            );
        }

        $incident->update(['statut' => $nouveauStatut]);

        Log::create([
            'action'       => "Incident #{$incident->id} passé à '{$nouveauStatut}'",
            'date_action'  => now()->toDateString(),
            'heure_action' => now()->toTimeString(),
            'user_id'      => $userId,
            'ip_address'   => $ip,
        ]);

        return $incident->fresh(['evaluationSalle','declarant','videos','rapport']);
    }
}
