<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiencias = Experience::all();
        return response()->json($experiencias, 200);
    }

    // NUEVO: Obtener solo las experiencias del prestador logueado
    public function myExperiences()
    {
        $experiencias = Experience::where('prestador_id', Auth::id())->get();
        return response()->json($experiencias, 200);
    }

    public function show($id)
    {
        $experiencia = Experience::find($id);
        if (!$experiencia) {
            return response()->json(['error' => 'Experiencia no encontrada'], 404);
        }
        return response()->json($experiencia, 200);
    }

    public function store(Request $request)
    {
        // Seguridad: Forzar que el prestador_id sea el del token y no el enviado por request
        $experiencia = Experience::create([
            'prestador_id' => Auth::id(), 
            'categoria' => $request->categoria,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'ubicacion' => $request->ubicacion,
            'status' => 'pendiente',
            'imagenes' => $request->imagenes
        ]);

        return response()->json(['mensaje' => 'Experiencia creada con éxito', 'data' => $experiencia], 201);
    }

    public function update(Request $request, $id)
    {
        $experiencia = Experience::find($id);

        if (!$experiencia) {
            return response()->json(['error' => 'Experiencia no encontrada para actualizar'], 404);
        }

        // 🔐 VALIDACIÓN DE SEGURIDAD: Si es prestador, solo puede editar la suya
        if (Auth::user()->role === 'prestador' && $experiencia->prestador_id !== Auth::id()) {
            return response()->json(['error' => 'Acceso denegado. No eres el dueño de esta experiencia.'], 403);
        }

        $experiencia->update($request->all());
        return response()->json(['mensaje' => 'Experiencia actualizada correctamente', 'data' => $experiencia], 200);
    }

    public function destroy($id)
    {
        $experiencia = Experience::find($id);

        if (!$experiencia) {
            return response()->json(['error' => 'Experiencia no encontrada para eliminar'], 404);
        }

        // 🔐 VALIDACIÓN DE SEGURIDAD: Prestador solo elimina la suya. Admin elimina cualquiera.
        if (Auth::user()->role === 'prestador' && $experiencia->prestador_id !== Auth::id()) {
            return response()->json(['error' => 'Acceso denegado. No tienes permiso para eliminar esta experiencia.'], 403);
        }

        $experiencia->delete();
        return response()->json(['mensaje' => 'Experiencia eliminada correctamente'], 200);
    }
}
