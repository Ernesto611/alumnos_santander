<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Alumno;

class BienvenidaAlumno extends Mailable
{
    use Queueable, SerializesModels;

    public $alumno;

    public function __construct(Alumno $alumno)
    {
        $this->alumno = $alumno;
    }

    public function build() {
        return $this->subject('Bienvenido a la universidad')
                    ->view('emails.bienvenida');
    }
}
