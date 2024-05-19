<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MyPageController extends Controller
{
    public function showMyPage()
    {
        // ログインユーザーを取得
        $user = Auth::user();

        // 予約情報を日付順、時間順に取得
        $orderedReservations = Reservation::where('user_id', $user->id)
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->map(function ($reservation) {
                // 予約情報のTimeをフォーマット
                $reservation->formattedTime = Carbon::createFromFormat('H:i:s', $reservation->time)->format('H:i');
                return $reservation;
            });

        // 予約情報があるかどうかを確認
        if ($orderedReservations->isNotEmpty()) {
            // 各予約に対してQRコードを生成する
            foreach ($orderedReservations as $reservation) {
                // QRコードを生成するためのデータを準備
                $qrCodeData = "Restaurant: " . $reservation->restaurant->name . "\n";
                $qrCodeData .= "User: " . $reservation->user->name . "\n";
                $qrCodeData .= "Date: " . $reservation->date . "\n";
                $qrCodeData .= "Time: " . $reservation->formattedTime . "\n";
                $qrCodeData .= "Number: " . $reservation->number . "\n\n";

                // QRコードを生成してBase64エンコードする
                $qrCode = QrCode::format('png')->encoding('UTF-8')->size(150)->generate($qrCodeData);
                $reservation->qrCodeBase64 = base64_encode($qrCode); // 予約にQRコードを追加する
            }
        } else {
            // 予約情報がない場合の処理
            $orderedReservations = collect();
        }

        // お気に入り情報を取得
        $favorites = Favorite::where('user_id', $user->id)->get();

        // お気に入り情報に対応する飲食店情報を取得
        $restaurants = $favorites->map(function ($favorite) {
            return $favorite->restaurant;
        });

        return view('mypage', compact('user', 'orderedReservations', 'restaurants'));
    }
}
