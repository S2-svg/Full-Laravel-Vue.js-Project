<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VatReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $periodName;

    public string $pdfPath;

    public array $summary;

    /**
     * Create a new message instance.
     */
    public function __construct(string $periodName, string $pdfPath, array $summary)
    {
        $this->periodName = $periodName;
        $this->pdfPath    = $pdfPath;
        $this->summary    = $summary;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Monthly VAT Declaration — {$this->periodName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.vat-report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $filename = 'VAT_Declaration_' . str_replace(' ', '_', $this->periodName) . '.pdf';

        return [
            Attachment::fromPath($this->pdfPath)
                ->as($filename)
                ->withMime('application/pdf'),
        ];
    }
}
