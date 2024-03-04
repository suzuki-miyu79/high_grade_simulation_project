<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Representative;
use App\Models\Reservation;
use App\Models\Area;
use App\Models\Genre;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class ShopRepresentativeController extends Controller
{
    public function index()
    {
        // ログインユーザー情報を取得
        $user = Auth::user();

        // 店舗代表者が管理している店舗を取得
        $managedRestaurants = $user->restaurants;

        // 店舗代表者が管理する店舗の予約情報を取得
        $reservations = $user->restaurants->flatMap->reservations;

        // 選択された店舗のIDを取得
        $selectedRestaurantId = request()->input('selected_restaurant_id');

        // 選択された店舗がない場合は、最初の店舗を選択する
        if (!$selectedRestaurantId && $managedRestaurants->isNotEmpty()) {
            $selectedRestaurantId = $managedRestaurants->first()->id;
        }

        // 選択された店舗のRestaurantテーブルの情報を取得
        $selectedRestaurant = null;
        $currentArea = null;
        $currentGenre = null;
        $areas = [];
        $genres = [];

        if ($selectedRestaurantId) {
            $selectedRestaurant = Restaurant::findOrFail($selectedRestaurantId);
            $reservations = $selectedRestaurant->reservations;
            $reservations = $reservations->sortBy(function ($reservation) {
                return Carbon::parse($reservation->date . ' ' . $reservation->time);
            });

            // 現在のエリアとジャンルを取得
            $currentArea = $selectedRestaurant->area;
            $currentGenre = $selectedRestaurant->genre;
        }

        // エリアとジャンルの選択肢を取得
        $areas = Area::all();
        $genres = Genre::all();

        return view('shop-management', compact('managedRestaurants', 'reservations', 'selectedRestaurant', 'selectedRestaurantId', 'currentArea', 'currentGenre', 'areas', 'genres'));
    }
}
