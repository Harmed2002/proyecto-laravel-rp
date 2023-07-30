<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function funIngresar(Request $request)
	{
		// Con Laravel Sanctum //

        // Los datos de login se guardan en la tabla personal_access_tokens
		// validamos los datos
		$credenciales = $request->validate([
			"email" => "required|email",
			"password" => "required"
		]);

        // Autenticación de usr
        if (!Auth::attempt($credenciales)){
            return response()->json(["message" => "Usuario no autenticado"]);
        }

        // Generación del Token
        $usuario = Auth::user();
        $token = $usuario->createToken("token personal")->plainTextToken;

        return response()->json([
            "access_token" => $token,
            "user" => $usuario
        ]);
	}

	public function funRegistro(Request $request)
	{
		// Con Laravel Sanctum //

		// validamos los datos
		$request->validate([
			"name" => "required",
			"email" => "required|email|unique:users",
			"password" => "required"
		]);

		// Guardamos
		$user = new User();
		$user->name = $request->name;
		$user->email = $request->email;
		$user->password = bcrypt($request->password);
		$user->save();

		return response()->json(["message" => "Usuario Registrado"], 201);
	}

	public function funPerfil(Request $request)
	{
        $usuario = Auth::user();

        return response()->json($usuario);
	}

	public function funSalir(Request $request)
	{
        // Eliminamo todos los tokens de la tabla personal_access_tokens
        Auth::user()->tokens()->delete();

        return response()->json(["message" => "Logout"]);
	}
}
