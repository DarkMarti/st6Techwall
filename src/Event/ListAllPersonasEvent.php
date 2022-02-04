<?php

namespace App\Event;

use App\Entity\Persona;
use Symfony\Contracts\EventDispatcher\Event;

class ListAllPersonasEvent extends Event{
    const LIST_ALL_PERSONA_EVENT = 'persona.list_alls';

    public function __construct(private int $nbPersona){}

    public function getNbPersona(): int{
        return $this->nbPersona;
    }
}

?>