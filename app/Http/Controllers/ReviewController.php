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
}