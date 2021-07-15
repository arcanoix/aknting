<?php

namespace Modules\Pos\Notifications;

use App\Abstracts\Notification;
use App\Models\Common\EmailTemplate;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class OrderReceipt extends Notification
{
    /**
     * The order model.
     *
     * @var object
     */
    public $order;

    /**
     * The email template.
     *
     * @var string
     */
    public $template;

    public function __construct($order = null)
    {
        parent::__construct();

        $this->order = $order;
        $this->template = EmailTemplate::alias('order_receipt_customer')->first();
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $message = $this->initMessage();

        $message->attach($this->storeReceiptPdfAndGetPath($this->order), [
            'mime' => 'application/pdf',
        ]);

        return $message;
    }

    public function getTags(): array
    {
        return [
            '{order_number}',
            '{company_name}',
            '{company_email}',
            '{company_tax_number}',
            '{company_phone}',
            '{company_address}',
        ];
    }

    public function getTagsReplacement(): array
    {
        return [
            $this->order->document_number,
            $this->order->company->name,
            $this->order->company->email,
            $this->order->company->tax_number,
            $this->order->company->phone,
            nl2br(trim($this->order->company->address)),
        ];
    }

    public function storeReceiptPdfAndGetPath($order): string
    {
        $view = view('pos::orders.receipt', compact('order'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getReceiptFileName($order);

        $pdf_path = storage_path('app/temp/' . $file_name);

        // Save the PDF file into temp folder
        $pdf->save($pdf_path);

        return $pdf_path;
    }

    public function getReceiptFileName($order, string $separator = '-', string $extension = 'pdf'): string
    {
        return $this->getSafeReceiptNumber($order, $separator) . $separator . time() . '.' . $extension;
    }

    public function getSafeReceiptNumber($order, string $separator = '-'): string
    {
        return Str::slug($order->number, $separator, language()->getShortCode());
    }
}
