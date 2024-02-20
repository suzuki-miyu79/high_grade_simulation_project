<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Representative;
use App\Models\Reservation;
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
        } else {
            $restaurantName = '代表者情報が見つかりません';
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

        return view('shop-management', compact('restaurantName', 'reservations'));
    }

    // 店舗画像の保存
    public function storeImage(Request $request)
    {
        // 画像を取得
        $image = $request->file('image');

        // 画像を保存
        $path = $image->store('images', 'public');

        // 画像の保存パスを返す
        return $path;
    }
}
