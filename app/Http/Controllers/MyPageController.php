<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyPageController extends Controller
{
    public function showMyPage()
    {
        // ログインユーザーを取得
        $user = Auth::user();

        // 予約情報を日付順に取得
        $numberedReservations = Reservation::where('user_id', $user->id)
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($reservation, $index) {
                // 予約情報のTimeをフォーマット
                $reservation->formattedTime = Carbon::createFromFormat('H:i:s', $reservation->time)->format('H:i');
                // 番号を付ける
                $reservation->setAttribute('number', $index + 1);
                return $reservation;
            });

        // お気に入り情報を取得
        $favorites = Favorite::where('user_id', $user->id)->get();

        // お気に入り情報に対応する飲食店情報を取得
        $restaurants = $favorites->map(function ($favorite) {
            return $favorite->restaurant;
        });

        return view('mypage', compact('user', 'numberedReservations', 'restaurants'));
    }
}
