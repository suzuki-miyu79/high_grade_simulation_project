<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Mail\ReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Jobs\ReminderMailJob;

class ReminderController extends Controller
{
    // リマインダー送信機能
    public function sendReminder()
    {
        // 明日の予約情報を取得する
        $tomorrow = Carbon::tomorrow();
        $reservations = Reservation::whereDate('date', $tomorrow)->get();

        // 関連するユーザー情報を取得してジョブをディスパッチする
        foreach ($reservations as $reservation) {
            ReminderMailJob::dispatch($reservation);
        }
    }
}
