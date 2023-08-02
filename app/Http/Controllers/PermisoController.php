<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function index()
    {
        $permisos = Permiso::get();

        return response()->json($permisos);
    }

    public function store(Request $request)
    {
        $request->validate(["nombre" => "required|unique:permisos"]);

        $permiso = new Permiso;
        $permiso->nombre = $request->nombre;
        $permiso->detalle = $request->detalle;
        $permiso->save();

        return response()->json(["message" => "El permiso ha sido registrado"], 201);
    }

    public function show(string $id)
    {
        $permiso = Permiso::findOrFail($id);

        return response()->json($permiso, 200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate(["nombre" => "required|unique:permisos,nombre,$id"]);

        $permiso = Permiso::findOrFail($id);
        $permiso->nombre = $request->nombre;
        $permiso->detalle = $request->detalle;
        $permiso->update();

        return response()->json(["message" => "El permiso ha sido actualizado"], 201);
    }

    public function destroy(string $id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();

        return response()->json(["message" => "El permiso ha sido eliminado"], 200);
    }
}
