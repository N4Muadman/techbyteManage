<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LateArrivalViolationNotice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

     public $employee;
     public $totalLateHours;
     public $totalLateTimes;
     public $type;
     public function __construct($employee, $totalLateHours, $totalLateTimes, $type)
     {
         $this->employee = $employee;
         $this->totalLateHours = $totalLateHours;
         $this->totalLateTimes = $totalLateTimes;
         $this->type = $type;
     }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông Báo Vi Phạm Đi Muộn',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.late_arrival_violation',
            with: [
                'employee' => $this->employee,
                'totalLateHours' => $this->totalLateHours,
                'totalLateTimes' => $this->totalLateTimes,
                'type' => $this->type,
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
