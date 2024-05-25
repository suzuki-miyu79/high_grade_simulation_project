<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Review;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    // 口コミ投稿ページ表示
    public function create($restaurant_id)
    {
        $restaurant = Restaurant::findOrFail($restaurant_id);
        return view('review.review', compact('restaurant'));
    }

    // 口コミ投稿機能
    public function store(Request $request, $restaurant_id)
    {
        // 口コミの保存
        $review = new Review();
        $review->user_id = auth()->id();
        $review->restaurant_id = $restaurant_id;
        $review->rating = $request->input('rating'); // 口コミの評価を設定
        $review->review = $request->input('review'); // 口コミの内容を設定

        if ($request->hasFile('image')) {
            // 画像を保存してパスを取得
            $imagePath = $request->file('image')->store('review_images', 'public');
            // パスを保存
            $review->image_path = asset('storage/' . $imagePath);
        }

        $review->save();

        return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('success', '口コミを投稿しました。');
    }

    // 口コミ編集機能
    public function edit($restaurant_id, $review_id)
    {
        // 編集する口コミを取得
        $review = Review::where('id', $review_id)->where('restaurant_id', $restaurant_id)->first();

        // レストラン情報を取得
        $restaurant = Restaurant::find($restaurant_id);

        // 口コミが存在しない、または現在のユーザーがこの口コミを編集する権限がない場合、リダイレクト
        if (!$review || auth()->user()->id !== $review->user_id) {
            return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('error', '編集権限がありません。');
        }

        // 編集フォームを表示する
        return view('review.edit', compact('review', 'restaurant'));
    }

    // 口コミ更新処理
    public function update(ReviewRequest $request, $restaurant_id, $review_id)
    {
        // 編集する口コミを取得
        $review = Review::where('id', $review_id)->where('restaurant_id', $restaurant_id)->first();

        // 口コミが存在しない、または現在のユーザーがこの口コミを編集する権限がない場合、リダイレクト
        if (!$review || auth()->user()->id !== $review->user_id) {
            return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('error', '編集権限がありません。');
        }

        // 口コミを更新
        $review->rating = $request->rating;
        $review->review = $request->review;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $review->image = $imagePath;
        }

        $review->save();

        return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('success', '口コミを更新しました。');
    }

    // 口コミ削除機能
    public function destroy($restaurant_id, $review_id)
    {
        // 削除する口コミを取得
        $review = Review::where('id', $review_id)->where('restaurant_id', $restaurant_id)->first();

        // 口コミが存在しない、または現在のユーザーがこの口コミを削除する権限がない場合、リダイレクト
        if (!$review || (auth()->user()->id !== $review->user_id && !auth()->user()->is_admin)) {
            return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('error', '削除権限がありません。');
        }

        $review->delete();

        return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('success', '口コミを削除しました。');
    }

    // 口コミ取得機能
    public function fetchReviews($restaurant_id)
    {
        // 現在のユーザーの口コミを除いた口コミを取得
        $reviews = Review::where('restaurant_id', $restaurant_id)
            ->where('user_id', '!=', auth()->id())
            ->get();

        return response()->json(['reviews' => $reviews]);
    }
}
