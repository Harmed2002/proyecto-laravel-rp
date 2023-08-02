<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // El limite viene en la ruta: /api/user?limit=5&page=2&q=juan
        $limit = isset($request->limit) ? $request->limit : 10;
        $buscar = isset($request->q) ? $request->q : "";

        if ($buscar) {
            $usuarios = User::orderBy("id", "desc")->where("email", "like", "%$buscar%")->paginate($limit);
        } else {
            $usuarios = User::orderBy("id", "desc")->paginate($limit);
        }

        return response()->json($usuarios, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required"
        ]);

        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->save();

        return response()->json(["message" => "Usuario Registrado"], 201);
    }

    public function show(string $id)
    {
        $usuario = User::findOrFail($id);

        return response()->json($usuario, 200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email,$id"
        ]);

        $usuario = User::findOrFail($id);
        $usuario->name = $request->name;
        $usuario->email = $request->email;

        if (isset($request->password)) {
            $usuario->password = bcrypt($request->password);
        }

        $usuario->update();

        return response()->json(["message" => "Usuario Actualizado"]);
    }

    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return response()->json(["message" => "Usuario Eliminado"]);
    }
}
