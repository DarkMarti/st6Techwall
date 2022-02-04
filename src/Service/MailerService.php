<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $replyTo;

    public function __construct(MailerInterface $mailer, $replyTo)
    {
        $this->replyTo = $replyTo;
    }
    public function sendEmail(
        $to = 'dam009.2019@gmail.com',
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $subject = 'Time for Symfony Mailer!'
    ): void{
    
        $email = (new Email())
            ->from('hello@example.com')
            ->to($to)            
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);

        $this->mailer->send($email);
    }
    
}

?>