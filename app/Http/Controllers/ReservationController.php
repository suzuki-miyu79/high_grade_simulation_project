<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Reservation;
use App\Http\Requests\ReservationRequest;

class ReservationController extends Controller
{
    // 予約追加機能
    public function store(ReservationRequest $request, $restaurant_id)
    {
        $request->session()->put('formData', $request->all());


        Reservation::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $restaurant_id,
            'date' => $request->input('reservation_date'),
            'time' => $request->input('reservation_time'),
            'number' => $request->input('reservation_number'),
        ]);

        return redirect()->route('reserved.show', ['restaurant_id' => $restaurant_id]);
    }

    // 予約完了ページ表示
    public function showReserved()
    {
        return view('reserved');
    }

    // 予約取消機能
    public function cancelReservation($reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);

        $reservation->delete();

        return redirect()->route('mypage')->with('success', 'Reservation canceled successfully.');
    }
}
