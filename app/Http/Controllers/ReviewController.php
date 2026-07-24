<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'experience'])->get();
        return view('admin.reviews', compact('reviews'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        $review->update(['approved' => $request->approved]);
        return redirect('/admin/reviews');
    }

    public function destroy($id)
    {
        Review::destroy($id);
        return redirect('/admin/reviews');
    }

    // 1. Esta función es nueva: Sirve para que la app lea las reseñas (JSON) en lugar de la vista web
    public function getForExperience($experience_id)
    {
        $reviews = \App\Models\Review::with('user')->where('experience_id', $experience_id)->get();
        return response()->json($reviews);
    }

    // 2. Esta función es la que te estaba dando el Error 500 porque no existía para guardar
    public function store(Request $request, $experience_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review = \App\Models\Review::create([
            'experience_id' => $experience_id, // Usamos el nombre correcto de tu BD
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'approved' => 1 
        ]);

        return response()->json([
            'message' => 'Reseña guardada con éxito',
            'data' => $review
        ], 201);
    }

}
