<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Envoi d'un email de confirmation de réservation
     */
    public function sendReservationEmail(array $reservation): void
    {
        $body = "Chèr(e) {$reservation['clientName']},\n\nVotre réservation pour le {$reservation['dateReservation']} a été confirmée.\n\nMerci de votre réservation.";
            
    
        $email = (new Email())
            ->from('no-reply@example.com')
            // ->to($reservation['email'])
            ->to('test@example.com')
            ->subject('Confirmation de réservation')
            ->text($body);

        $this->mailer->send($email);
    }

    /**
     * Envoi d'un email d'annulation de réservation
     */
    public function sendCancelReservationEmail(array $reservation): void
    {
        $body = "Chèr(e) " . $reservation['clientName'] . ",\n\nVotre annulation de la réservation pour le " . $reservation['dateReservation'] . " a été confirmée.\n\n";

        $email = (new Email())
            ->from('no-reply@example.com')
            // ->to($reservation['email'])
            ->to('test@example.com')
            ->subject('Annulation de réservation')
            ->text($body);

        $this->mailer->send($email);
    }


    
}