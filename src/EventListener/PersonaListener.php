<?php

namespace App\EventListener;

use App\Event\AddPersonaEvent;
use App\Event\ListAllPersonasEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class PersonaListener{

    public function __construct(
        private LoggerInterface $logger
    ){}

    public function onPersonaAdd(AddPersonaEvent $event){
        $this->logger->debug("Evento que añade una persona ". $event->getPersona()->getName());
    }

    public function onListAllPersonas(ListAllPersonasEvent $event){
        $this->logger->debug("Evento que muestra una lista de personas ". $event->getNbPersona());
    }

    public function logKernelRequest(KernelEvent $event){
        $this->logger->debug("Evento que muestra una lista de personas ". $event->getRequest());
    }
}
?>