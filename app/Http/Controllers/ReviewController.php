<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // INVITADOS Y TODOS: Ver las reseñas de una experiencia específica
    public function index($experience_id)
    {
        $reviews = Review::where('experience_id', $experience_id)->with('user')->get();
        return response()->json($reviews, 200);
    }

    // TURISTA: Crear una reseña
    public function store(Request $request, $experience_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);

        // Verificamos que la experiencia exista
        if (!Experience::find($experience_id)) {
            return response()->json(['error' => 'Experiencia no encontrada'], 404);
        }

        $review = Review::create([
            'experience_id' => $experience_id,
            'user_id' => Auth::id(), // El ID lo tomamos directo del token por seguridad
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json(['mensaje' => 'Reseña creada con éxito', 'data' => $review], 201);
    }

    // TURISTA: Editar su propia reseña
    public function update(Request $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['error' => 'Reseña no encontrada'], 404);
        }

        // ¡Seguridad extra! Verificamos que el turista sea el dueño de la reseña
        if ($review->user_id !== Auth::id()) {
            return response()->json(['error' => 'Solo puedes editar tus propias reseñas'], 403);
        }

        $review->update($request->only(['rating', 'comment']));

        return response()->json(['mensaje' => 'Reseña actualizada', 'data' => $review], 200);
    }

    // ADMIN: Eliminar cualquier reseña (por lenguaje inapropiado, etc.)
    public function destroy($id)
    {
        $review = Review::find($id);
        
        if (!$review) {
            return response()->json(['error' => 'Reseña no encontrada'], 404);
        }

        $review->delete();
        return response()->json(['mensaje' => 'Reseña eliminada por el administrador'], 200);
    }
}
