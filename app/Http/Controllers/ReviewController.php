<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;

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

        if ($request->hasFile('image')) {
            // 画像を保存してパスを取得
            $imagePath = $request->file('image')->store('review_images', 'public');
            // パスを保存
            $review->image_path = asset('storage/' . $imagePath);
        }

        $review->save;

        return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('success', '口コミを投稿しました。');
    }
}
