<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['success'=>true,'data'=>Departement::with('formations')->paginate(15)]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['nom_dept'=>'required|string|unique:departements,nom_dept']);
        return response()->json(['success'=>true,'data'=>Departement::create($data)], 201);
    }

    public function show(Departement $departement): JsonResponse
    {
        return response()->json(['success'=>true,'data'=>$departement->load('formations')]);
    }

    public function update(Request $request, Departement $departement): JsonResponse
    {
        $data = $request->validate(['nom_dept'=>'required|string|unique:departements,nom_dept,'.$departement->id]);
        $departement->update($data);
        return response()->json(['success'=>true,'data'=>$departement]);
    }

    public function destroy(Departement $departement): JsonResponse
    {
        $departement->delete();
        return response()->json(['success'=>true,'message'=>'Département supprimé.']);
    }
}