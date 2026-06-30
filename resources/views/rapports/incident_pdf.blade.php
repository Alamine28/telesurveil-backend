<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body     { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1       { text-align: center; color: #1a3a5c; border-bottom: 2px solid #1a3a5c; padding-bottom: 8px; }
        h3       { color: #1a3a5c; margin-top: 20px; }
        table    { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td   { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
        th       { background: #f1f5f9; }
        .footer  { margin-top: 40px; text-align: right; font-size: 10px; color: #888; }
        .declare  { background: #dbeafe; color: #1e40af; padding: 3px 8px; border-radius: 4px; }
        .confirme { background: #dcfce7; color: #166534; padding: 3px 8px; border-radius: 4px; }
        .rejette  { background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; }
        .en_cours { background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Rapport d'Incident — UFR SATIC / UADB</h1>
    <table>
        <tr>
            <th>N° Incident</th><td>#{{ $incident->id }}</td>
            <th>Date rapport</th><td>{{ $date_rapport }}</td>
        </tr>
        <tr>
            <th>Catégorie</th><td>{{ $incident->categorie }}</td>
            <th>Statut</th>
            <td><span class="{{ $incident->statut }}">{{ strtoupper($incident->statut) }}</span></td>
        </tr>
        <tr>
            <th>Date incident</th><td>{{ $incident->date_incident }}</td>
            <th>Heure</th><td>{{ $incident->heure_incident }}</td>
        </tr>
        <tr>
            <th>Salle</th><td>{{ $incident->evaluationSalle->salle->nom_salle ?? '—' }}</td>
            <th>Déclaré par</th>
            <td>{{ $incident->declarant->prenom ?? '' }} {{ $incident->declarant->nom ?? '' }}</td>
        </tr>
    </table>

    <h3>Description de l'incident</h3>
    <p>{{ $incident->description }}</p>

    <h3>Résumé du rapport</h3>
    <p>{{ $description }}</p>

    @if($incident->videos->count())
    <h3>Preuves vidéo</h3>
    <table>
        <tr><th>#</th><th>Fichier</th><th>Date</th><th>Timestamp</th></tr>
        @foreach($incident->videos as $video)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ basename($video->chemin_fichier) }}</td>
            <td>{{ $video->date_enreg }}</td>
            <td>{{ $video->pivot->timestamp ? gmdate('H:i:s', $video->pivot->timestamp) : '—' }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <div class="footer">
        Généré le {{ now()->format('d/m/Y à H:i') }} — Système de Télésurveillance UFR SATIC
    </div>
</body>
</html>