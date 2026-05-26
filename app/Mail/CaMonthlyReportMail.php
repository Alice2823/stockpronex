<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CaMonthlyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $caName;
    public $businessName;
    public $month;
    public $pdfData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($caName, $businessName, $month, $pdfData)
    {
        $this->caName = $caName;
        $this->businessName = $businessName;
        $this->month = $month;
        $this->pdfData = $pdfData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Monthly Business Report - ' . $this->businessName)
                    ->markdown('emails.ca-report')
                    ->attachData($this->pdfData, 'Profit_Report_' . $this->month . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
