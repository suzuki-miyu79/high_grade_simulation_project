<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Review;

class ReviewController extends Controller
{
    public function create($restaurant_id)
    {
        $restaurant = Restaurant::findOrFail($restaurant_id);
        return view('review', compact('restaurant'));
    }
    public function store(Request $request, $restaurant_id)
    {
        $review = new Review();
        $review->user_id = auth()->id();
        $review->restaurant_id = $restaurant_id;
        $review->review = $request->input('review');
        $review->rating = $request->input('rating');
        $review->save;

        return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('success', '口コミを投稿しました。');
    }
}
