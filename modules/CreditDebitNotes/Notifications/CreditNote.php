<?php

namespace Modules\CreditDebitNotes\Notifications;

use App\Abstracts\Notification;
use App\Models\Common\EmailTemplate;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Modules\CreditDebitNotes\Traits\Documents;

class CreditNote extends Notification
{
    use Documents;

    /**
     * The credit note model.
     *
     * @var object
     */
    public $credit_note;

    /**
     * The email template.
     *
     * @var string
     */
    public $template;

    /**
     * Should attach pdf or not.
     *
     * @var bool
     */
    public $attach_pdf;

    /**
     * Create a notification instance.
     *
     * @param  object  $credit_note
     * @param  object  $template_alias
     * @param  object  $attach_pdf
     */
    public function __construct($credit_note = null, $template_alias = null, $attach_pdf = false)
    {
        parent::__construct();

        $this->credit_note = $credit_note;
        $this->template = EmailTemplate::alias($template_alias)->first();
        $this->attach_pdf = $attach_pdf;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $message = $this->initMessage();

        // Attach the PDF file
        if ($this->attach_pdf) {
            $message->attach($this->storeCreditNotePdfAndGetPath($this->credit_note), [
                'mime' => 'application/pdf',
            ]);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'credit_note_id' => $this->credit_note->id,
            'amount' => $this->credit_note->amount,
        ];
    }

    public function getTags()
    {
        return [
            '{document_number}',
            '{credit_note_total}',
            '{credit_note_issue_date}',
            '{credit_note_guest_link}',
            '{credit_note_admin_link}',
            '{credit_note_portal_link}',
            '{customer_name}',
            '{company_name}',
            '{company_email}',
            '{company_tax_number}',
            '{company_phone}',
            '{company_address}',
        ];
    }

    public function getTagsReplacement()
    {
        return [
            $this->credit_note->document_number,
            money($this->credit_note->amount, $this->credit_note->currency_code, true),
            company_date($this->credit_note->issued_at),
            URL::signedRoute('signed.credit-debit-notes.credit-notes.show', [$this->credit_note->id]),
            route('credit-debit-notes.credit-notes.show', $this->credit_note->id),
            route('portal.credit-debit-notes.credit-notes.show', $this->credit_note->id),
            $this->credit_note->contact_name,
            $this->credit_note->company->name,
            $this->credit_note->company->email,
            $this->credit_note->company->tax_number,
            $this->credit_note->company->phone,
            nl2br(trim($this->credit_note->company->address)),
        ];
    }
}
