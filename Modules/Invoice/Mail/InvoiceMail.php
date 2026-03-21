<?php

namespace Modules\Invoice\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Invoice\Models\Invoice;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The invoice instance.
     *
     * @var Invoice
     */
    public Invoice $invoice;

    /**
     * The PDF content of the invoice.
     *
     * @var string|null
     */
    public ?string $pdfContent;

    /**
     * Custom email message.
     *
     * @var string|null
     */
    public ?string $customMessage;

    /**
     * Create a new message instance.
     *
     * @param Invoice $invoice
     * @param string|null $pdfContent
     * @param string|null $customMessage
     */
    public function __construct(Invoice $invoice, ?string $pdfContent = null, ?string $customMessage = null)
    {
        $this->invoice = $invoice;
        $this->pdfContent = $pdfContent;
        $this->customMessage = $customMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        $email = $this->subject('Invoice ' . $this->invoice->invoice_number)
            ->view('modules.invoice::emails.invoice')
            ->with([
                'invoice' => $this->invoice,
                'customMessage' => $this->customMessage,
            ]);

        // Attach PDF if provided
        if ($this->pdfContent) {
            $email->attachData(
                $this->pdfContent,
                $this->invoice->invoice_number . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );
        }

        return $email;
    }

    /**
     * Set the invoice as viewed when email is sent.
     *
     * @return void
     */
    public function markAsViewed(): void
    {
        if ($this->invoice->status === Invoice::STATUS_SENT) {
            $this->invoice->status = Invoice::STATUS_VIEWED;
            $this->invoice->save();
        }
    }
}
