<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    // 店舗情報新規登録
    public function store(Request $request)
    {
        // ユーザーIDを取得
        $userId = auth()->id();

        // 画像のアップロード処理
        if ($request->hasFile('image')) {
            // 画像を保存してパスを取得
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // 新しい店舗を作成
        $restaurant = new Restaurant();
        $restaurant->name = $request->input('name');
        $restaurant->area_id = $request->input('area');
        $restaurant->genre_id = $request->input('genre');
        $restaurant->overview = $request->input('overview');
        $restaurant->user_id = $userId;
        // 画像がアップロードされていれば、パスを保存
        if (isset($imagePath)) {
            $restaurant->image = $imagePath;
        }
        $restaurant->save();

        // 成功メッセージをフラッシュしてリダイレクト
        return redirect()->back()->with('success', '店舗が登録されました。');
    }

    // 店舗情報更新
    public function update(Request $request, $id)
    {
        // 画像のアップロード処理
        if ($request->hasFile('image')) {
            // 画像を保存してパスを取得
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // 既存の店舗情報を取得
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->name = $request->input('name');
        $restaurant->area_id = $request->input('area');
        $restaurant->genre_id = $request->input('genre');
        $restaurant->overview = $request->input('overview');
        // 画像がアップロードされていれば、パスを保存
        if (isset($imagePath)) {
            $restaurant->image = asset('storage/' . $imagePath);
        }
        $restaurant->save();

        // 成功メッセージをフラッシュしてリダイレクト
        return redirect()->back()->with('success', '店舗情報が更新されました。');
    }

    // 飲食店一覧取得
    public function index(Request $request)
    {
        $areas = Area::whereIn('id', function ($query) {
            $query->select('area_id')->from('restaurants')->distinct();
        })->get();

        $genres = Genre::whereIn('id', function ($query) {
            $query->select('genre_id')->from('restaurants')->distinct();
        })->get();

        $restaurants = Restaurant::when($request->area, function ($query, $area) {
            return $query->whereHas('area', function ($subquery) use ($area) {
                $subquery->where('prefectures_name', $area);
            });
        })
            ->when($request->genre, function ($query, $genre) {
                return $query->whereHas('genre', function ($subquery) use ($genre) {
                    $subquery->where('genre_name', $genre);
                });
            })
            ->when($request->keyword, function ($query, $keyword) {
                return $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->get();

        return view('restaurant-list', compact('restaurants', 'areas', 'genres'));
    }

    // 飲食店詳細取得
    public function showDetail($restaurant_id)
    {
        // 一覧ページで選択した飲食店の詳細情報を取得
        $restaurant = Restaurant::findOrFail($restaurant_id);
        $user = Auth::user();

        $userReview = null;
        if ($user) {
            // ユーザーがこの店舗に対して既に口コミを投稿しているかを確認
            $userReview = Review::where('restaurant_id', $restaurant_id)
                ->where('user_id', $user->id)
                ->first();
        }

        return view('restaurant-detail', compact('restaurant', 'userReview'));
    }
}
