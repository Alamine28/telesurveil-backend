<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CameraController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['success'=>true,'data'=>Camera::with('salle')->paginate(15)]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom_cam'    => 'required|string|max:100',
            'adresse_ip' => 'required|ip|unique:cameras,adresse_ip',
            'salle_id'   => 'required|exists:salles,id',
        ]);
        return response()->json(['success'=>true,'data'=>Camera::create($data)->load('salle')], 201);
    }

    public function show(Camera $camera): JsonResponse
    {
        return response()->json(['success'=>true,'data'=>$camera->load('salle','videos')]);
    }

    public function update(Request $request, Camera $camera): JsonResponse
    {
        $data = $request->validate([
            'nom_cam'    => 'sometimes|string|max:100',
            'adresse_ip' => 'sometimes|ip|unique:cameras,adresse_ip,'.$camera->id,
            'salle_id'   => 'sometimes|exists:salles,id',
        ]);
        $camera->update($data);
        return response()->json(['success'=>true,'data'=>$camera->load('salle')]);
    }

    public function destroy(Camera $camera): JsonResponse
    {
        $camera->delete();
        return response()->json(['success'=>true,'message'=>'Caméra supprimée.']);
    }
}