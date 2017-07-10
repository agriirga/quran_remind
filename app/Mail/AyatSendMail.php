<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AyatSendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $indonesian_ayat, $ayat_surat , $arabic_ayat;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($indonesian_ayat, $ayat_surat, $arabic_ayat)
    {
        $this->indonesian_ayat = $indonesian_ayat;
        $this->ayat_surat = $ayat_surat;
        $this->arabic_ayat = $arabic_ayat;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Quran_Reminder')
            ->view('email.ayat');
    }
}
