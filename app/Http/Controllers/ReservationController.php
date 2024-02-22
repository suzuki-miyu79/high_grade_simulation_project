<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Reservation;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class ReservationController extends Controller
{
    // 予約追加機能
    public function store(ReservationRequest $request, $restaurant_id)
    {
        $request->session()->put('formData', $request->all());

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $restaurant_id,
            'date' => $request->input('reservation_date'),
            'time' => $request->input('reservation_time'),
            'number' => $request->input('reservation_number'),
        ]);

        // 予約情報を含む文字列を作成
        $reservationData = "Reservation ID: " . $reservation->id . ", User: " . $reservation->user->name . ", Date: " . $reservation->date . ", Time: " . $reservation->time;

        // QRコードを生成して保存
        $reservationData = mb_convert_encoding($reservationData, 'UTF-8');
        $qrCode = QrCode::size(200)->generate($reservationData);
        $qrCodePath = 'storage/qrcodes/reservation_' . $reservation->id . '.png';
        Storage::put($qrCodePath, $qrCode);

        // 予約にQRコードのパスを保存
        $reservation->qr_code_path = $qrCodePath;
        $reservation->save();

        return redirect()->route('reserved.show', ['restaurant_id' => $restaurant_id]);
    }

    // 予約完了ページ表示
    public function showReserved()
    {
        return view('reserved');
    }

    // 予約取消機能
    public function cancelReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $reservation->delete();

        return redirect()->route('mypage')->with('success', 'Reservation canceled successfully.');
    }

    // 予約内容変更機能
    public function updateReservation(ReservationRequest $request, $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        // 予約情報を更新
        $reservation->update([
            'date' => $request->input('reservation_date'),
            'time' => $request->input('reservation_time'),
            'number' => $request->input('reservation_number'),
        ]);

        return redirect()->route('mypage')->with('success', 'Reservation updated successfully.');
    }
}
