<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        Review::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $request->input('restaurant_id'),
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', '評価とコメントが送信されました。');
    }
}
