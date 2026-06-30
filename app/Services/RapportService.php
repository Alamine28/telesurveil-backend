<?php

namespace App\Services;

use App\Models\Rapport;
use App\Models\Incident;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RapportService
{
    public function generer(array $data, int $userId): Rapport
    {
        $incident = Incident::with(['evaluationSalle.salle','declarant','videos'])
                            ->findOrFail($data['incident_id']);

        $pdf      = Pdf::loadView('rapports.incident_pdf', [
            'incident'    => $incident,
            'description' => $data['description'],
            'date_rapport'=> now()->toDateString(),
        ]);

        $filename = "rapport_incident_{$incident->id}_" . now()->format('Ymd_His') . '.pdf';
        $path     = "rapports/{$filename}";

        Storage::disk('local')->put($path, $pdf->output());

        return Rapport::create([
            'description' => $data['description'],
            'date_rapport'=> now()->toDateString(),
            'fichier_pdf' => $path,
            'incident_id' => $incident->id,
            'user_id'     => $userId,
        ]);
    }

    public function cheminPdf(Rapport $rapport): string
    {
        return Storage::disk('local')->path($rapport->fichier_pdf);
    }
}