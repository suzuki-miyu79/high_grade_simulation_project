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

        // 口コミが存在しない、または現在のユーザーがこの口コミを編集する権限がない場合、リダイレクト
        if (!$review || auth()->user()->id !== $review->user_id) {
            return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('error', '編集権限がありません。');
        }

        // 編集フォームを表示する
        return view('reviews.edit', compact('review'));
    }

    // 口コミ更新処理
    public function update(Request $request, $restaurant_id, $review_id)
    {
        // フォームからの入力を検証する
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 更新する口コミを取得
        $review = Review::where('id', $review_id)->where('restaurant_id', $restaurant_id)->first();

        // 口コミが存在しない、または現在のユーザーがこの口コミを編集する権限がない場合、リダイレクト
        if (!$review || auth()->user()->id !== $review->user_id) {
            return redirect()->route('restaurant.detail', ['restaurant_id' => $restaurant_id])->with('error', '編集権限がありません。');
        }

        // 口コミの評価と内容を更新
        $review->rating = $request->input('rating');
        $review->review = $request->input('review');

        // 画像がアップロードされた場合、その画像を保存しパスを更新
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('review_images', 'public');
            $review->image_path = asset('storage/' . $imagePath);
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
}
