<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Restaurant;
use App\Models\Favorite;

class RestaurantController extends Controller
{
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

        return view('restaurant-detail', compact('restaurant'));
    }
}
