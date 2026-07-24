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

    // Agrega esta función en ReviewController.php
    public function store(Request $request, $experience_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review = Review::create([
            'experience_id' => $experience_id, // 🌟 Coincide con el fillable de su modelo
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
