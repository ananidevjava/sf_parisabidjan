<?php

namespace App\Service\Mail;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function send(string $to, string $subject, string $template, array $context): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address("patransit@site.com", "Paris Abidjan"))
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("$template")
            ->context($context);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }
    }
}
