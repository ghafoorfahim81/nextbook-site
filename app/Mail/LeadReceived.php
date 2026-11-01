<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\DemoRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Tells the team that a visitor sent a demo request or a message. Replying to the email
 * answers the visitor, when they gave an address.
 */
class LeadReceived extends Mailable
{
    public function __construct(public DemoRequest|ContactMessage $lead)
    {
    }

    public function isDemo(): bool
    {
        return $this->lead instanceof DemoRequest;
    }

    public function envelope(): Envelope
    {
        $subject = $this->isDemo()
            ? 'درخواست دیمو تازه: '.$this->lead->name
            : 'پیام تازه از سایت: '.$this->lead->name;

        return new Envelope(
            subject: $subject,
            replyTo: $this->lead->email ? [new Address($this->lead->email, $this->lead->name)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.lead',
            with: [
                'lead' => $this->lead,
                'isDemo' => $this->isDemo(),
                'adminUrl' => url($this->isDemo() ? '/admin/demo-requests' : '/admin/contact-messages'),
            ],
        );
    }
}
