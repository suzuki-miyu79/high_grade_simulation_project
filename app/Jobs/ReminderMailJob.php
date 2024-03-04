<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Mail\ReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReminderMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservation;
    protected $appointmentTime;

    /**
     * Create a new job instance.
     *
     * @param Reservation $reservation
     * @param string $appointmentTime
     */
    public function __construct(Reservation $reservation, $appointmentTime)
    {
        $this->reservation = $reservation;
        $this->appointmentTime = $appointmentTime;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // メール送信処理
        $user = $this->reservation->user;
        $email = $user->email;
        $name = $user->name;
        $restaurantName = $this->reservation->restaurant->name;
        $appointmentTime = $this->appointmentTime;

        Mail::to($email)->send(new ReminderMail($name, $restaurantName, $appointmentTime, $this->reservation));
    }
}