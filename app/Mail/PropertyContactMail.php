<?php

namespace App\Mail;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PropertyContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $property;
    public $contactData;

    public function __construct(Property $property, array $contactData)
    {
        $this->property = $property;
        $this->contactData = $contactData;
    }

    public function envelope(): Envelope
    {
        $replyTo = filled($this->contactData['email'] ?? null)
            ? [new Address($this->contactData['email'])]
            : [];

        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name'),
            ),
            replyTo: $replyTo,
            subject: 'Interés en el inmueble [Cod: ' . $this->property->codigo . ']',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.property-contact',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
