<?php

namespace App\EventSubscriber;

use App\Event\AddPersonaEvent;
use App\Service\MailerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonaEventSubscriber implements EventSubscriberInterface{

    public function __construct(
        private MailerService $mailer,
        private LoggerInterface $logger
        ){}

    public static function getSubscribedEvents(): array
    {
        return [
            AddPersonaEvent::ADD_PERSONA_EVENT => ['onAddPersonaEvent', 3000]
        ];
    }

    public function onAddPersonaEvent(AddPersonaEvent $event){
        $persona = $event->getPersona();
        $mailMessage = $persona->getFirstname().' '.$persona->getName().' fue agregado con éxito.'; 
        $this->logger->info("Correo enviado por ". $persona->getFirstname().' '.$persona->getName());
        $this->mailer->sendEmail($mailMessage, subject: 'Mail sent from EventSubscriber');
    }

}
?>