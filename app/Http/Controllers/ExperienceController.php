<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    // 1. GET (Todos): Mostrar todas las experiencias
    public function index()
    {
        $experiencias = Experience::all();
        return response()->json($experiencias, 200);
    }

    // 2. GET (Uno): Mostrar una experiencia específica por su ID
    public function show($id)
    {
        $experiencia = Experience::find($id);

        if (!$experiencia) {
            return response()->json(['error' => 'Experiencia no encontrada'], 404);
        }

        return response()->json($experiencia, 200);
    }

    // 3. POST: Crear una nueva experiencia
    public function store(Request $request)
    {
        $experiencia = Experience::create([
            'prestador_id' => $request->prestador_id,
            'categoria' => $request->categoria,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'ubicacion' => $request->ubicacion,
            'status' => 'pendiente',
            'imagenes' => $request->imagenes
        ]);

        return response()->json([
            'mensaje' => 'Experiencia creada con éxito',
            'data' => $experiencia
        ], 201);
    }

    // 4. PUT: Actualizar una experiencia existente
    public function update(Request $request, $id)
    {
        $experiencia = Experience::find($id);

        if (!$experiencia) {
            return response()->json(['error' => 'Experiencia no encontrada para actualizar'], 404);
        }

        // Actualiza solo los campos que se envíen en la petición
        $experiencia->update($request->all());

        return response()->json([
            'mensaje' => 'Experiencia actualizada correctamente',
            'data' => $experiencia
        ], 200);
    }

    // 5. DELETE: Eliminar una experiencia
    public function destroy($id)
    {
        $experiencia = Experience::find($id);

        if (!$experiencia) {
            return response()->json(['error' => 'Experiencia no encontrada para eliminar'], 404);
        }

        $experiencia->delete();

        return response()->json(['mensaje' => 'Experiencia eliminada correctamente'], 200);
    }
}
