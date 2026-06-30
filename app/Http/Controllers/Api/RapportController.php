<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Services\RapportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RapportController extends Controller
{
    public function __construct(private RapportService $rapportService) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => Rapport::with(['incident','auteur'])->latest()->paginate(20),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'description' => 'required|string',
            'incident_id' => 'required|exists:incidents,id',
        ]);
        $rapport = $this->rapportService->generer($data, $request->user()->id);
        return response()->json(['success'=>true,'data'=>$rapport->load('incident')], 201);
    }

    public function download(Rapport $rapport): BinaryFileResponse
    {
        $path = $this->rapportService->cheminPdf($rapport);
        return response()->download($path, basename($path), ['Content-Type'=>'application/pdf']);
    }
}