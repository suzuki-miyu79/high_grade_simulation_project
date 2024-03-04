<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    //受け取る変数
    public $name;
    public $restaurantName;
    public $appointmentTime;
    public $reservation;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $restaurantName
     * @param string $appointmentTime
     * @param Reservation $reservation
     */
    public function __construct($name, $restaurantName, $appointmentTime, Reservation $reservation)
    {
        //変数に受け取った値をセット
        $this->name = $name;
        $this->restaurantName = $restaurantName;
        $this->appointmentTime = $appointmentTime;
        $this->reservation = $reservation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '明日のご予約のお知らせ',
            from: new Address('authentication.laravel@gmail.com', 'Rese'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.reminder',
            with: [
                'name' => $this->name,
                'restaurantName' => $this->restaurantName,
                'appointmentTime' => $this->appointmentTime,
                'reservation' => $this->reservation
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
