<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Representative;
use App\Models\Reservation;
use App\Models\Area;
use App\Models\Genre;
use Carbon\Carbon;


class ShopRepresentativeController extends Controller
{
    public function index()
    {
        // ログインしているユーザーのIDを取得
        $userId = auth()->id();

        // ユーザーに紐づく代表者情報を取得
        $representative = Representative::where('user_id', $userId)->first();

        if ($representative) {
            // 代表者が管理する飲食店の名前を取得
            $restaurantName = $representative->restaurant->name ?? '店舗が設定されていません';

            // 店舗情報を取得
            $restaurant = Restaurant::findOrFail($representative->restaurant_id);

            // 現在のエリアとジャンルを取得
            $currentArea = $restaurant->area;
            $currentGenre = $restaurant->genre;

            // エリアとジャンルの選択肢を取得
            $areas = Area::all();
            $genres = Genre::all();
        } else {
            $restaurantName = '代表者情報が見つかりません';
            $restaurant = null;
            $currentArea = null;
            $currentGenre = null;
            $areas = [];
            $genres = [];
        }

        // 選択した店舗の予約情報を取得
        $reservations = Reservation::where('restaurant_id', $representative->restaurant_id ?? null)
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            // 予約情報の時間をフォーマットする
            ->map(function ($reservation) {
                $reservation->time = Carbon::parse($reservation->time)->format('H:i');
                return $reservation;
            });

        return view('shop-management', compact('restaurantName', 'reservations', 'restaurant', 'currentArea', 'currentGenre', 'areas', 'genres'));
    }
}
