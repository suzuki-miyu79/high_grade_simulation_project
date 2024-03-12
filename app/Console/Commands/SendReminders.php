<?php

namespace App\Console\Commands;

use App\Jobs\ReminderMailJob;
use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\ReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リマインダーの送信';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 明日の予約情報を取得する
        $tomorrow = now()->addDay();
        $reservations = Reservation::whereDate('date', $tomorrow)->get();

        // 関連する予約情報を取得してジョブをディスパッチする
        foreach ($reservations as $reservation) {
            // 時刻を変換してジョブに渡す
            $appointmentTime = Carbon::parse($reservation->time)->format('H:i');
            // ジョブをディスパッチ
            ReminderMailJob::dispatch($reservation, $appointmentTime);
        }
    }
}