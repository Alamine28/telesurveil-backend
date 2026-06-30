<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['success'=>true,'data'=>Salle::with('cameras')->paginate(15)]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom_salle' => 'required|string|unique:salles,nom_salle',
            'capacite'  => 'required|integer|min:1',
        ]);
        return response()->json(['success'=>true,'data'=>Salle::create($data)], 201);
    }

    public function show(Salle $salle): JsonResponse
    {
        return response()->json(['success'=>true,'data'=>$salle->load('cameras')]);
    }

    public function update(Request $request, Salle $salle): JsonResponse
    {
        $data = $request->validate([
            'nom_salle' => 'sometimes|string|unique:salles,nom_salle,'.$salle->id,
            'capacite'  => 'sometimes|integer|min:1',
        ]);
        $salle->update($data);
        return response()->json(['success'=>true,'data'=>$salle]);
    }

    public function destroy(Salle $salle): JsonResponse
    {
        $salle->delete();
        return response()->json(['success'=>true,'message'=>'Salle supprimée.']);
    }
}