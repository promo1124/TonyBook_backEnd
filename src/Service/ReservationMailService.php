<?php

namespace App\Service;

use App\Entity\Reservation;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ReservationMailService
{
    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        //$this->twig = $twig;
    }

    public function validerReservation(Reservation $reservation): void
    {
        $email = (new Email())
            ->from('owner@exemple.com')
            ->to('client@exemple.com') 
            ->subject('Réservation Validée')
            // ->html(
            //     $this->twig->render('emails/reservation_validee.html.twig', [
            //         'reservation' => $reservation,
            //     ])
            // )
            ;

        $this->mailer->send($email);
    }

    public function refuserReservation(Reservation $reservation): void
    {
        $email = (new Email())
            ->from('owner@exemple.com')
            ->to('client@exemple.com') 
            ->subject('Réservation Refusée')
            // ->html(
            //     $this->twig->render('emails/reservation_refusee.html.twig', [
            //         'reservation' => $reservation,
            //     ])
            // )
            ;

        $this->mailer->send($email);
    }
}