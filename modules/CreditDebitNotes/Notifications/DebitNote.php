<?php

namespace Modules\CreditDebitNotes\Notifications;

use App\Abstracts\Notification;
use App\Models\Common\EmailTemplate;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Modules\CreditDebitNotes\Traits\Documents;

class DebitNote extends Notification
{
    use Documents;

    /**
     * The debit note model.
     *
     * @var object
     */
    public $debit_note;

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
     * @param object $debit_note
     * @param object $template_alias
     * @param object $attach_pdf
     */
    public function __construct($debit_note = null, $template_alias = null, $attach_pdf = false)
    {
        parent::__construct();

        $this->debit_note = $debit_note;
        $this->template = EmailTemplate::alias($template_alias)->first();
        $this->attach_pdf = $attach_pdf;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $message = $this->initMessage();

        // Attach the PDF file
        if ($this->attach_pdf) {
            $message->attach($this->storeDebitNotePdfAndGetPath($this->debit_note), [
                'mime' => 'application/pdf',
            ]);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'debit_note_id' => $this->debit_note->id,
            'amount'        => $this->debit_note->amount,
        ];
    }

    public function getTags()
    {
        return [
            '{document_number}',
            '{debit_note_total}',
            '{debit_note_issue_date}',
            '{debit_note_guest_link}',
            '{debit_note_admin_link}',
            '{debit_note_portal_link}',
            '{vendor_name}',
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
            $this->debit_note->document_number,
            money($this->debit_note->amount, $this->debit_note->currency_code, true),
            company_date($this->debit_note->issued_at),
            URL::signedRoute('signed.credit-debit-notes.debit-notes.show', [$this->debit_note->id]),
            route('credit-debit-notes.debit-notes.show', $this->debit_note->id),
            route('portal.credit-debit-notes.debit-notes.show', $this->debit_note->id),
            $this->debit_note->contact_name,
            $this->debit_note->company->name,
            $this->debit_note->company->email,
            $this->debit_note->company->tax_number,
            $this->debit_note->company->phone,
            nl2br(trim($this->debit_note->company->address)),
        ];
    }
}
