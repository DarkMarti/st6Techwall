<?php

namespace App\Event;

use App\Entity\Persona;
use Symfony\Contracts\EventDispatcher\Event;

class AddPersonaEvent extends Event{

    const ADD_PERSONA_EVENT = 'persona.add';

    public function __construct(private Persona $persona){}

    public function getPersona(): Persona{
        return $this->persona;
    }
}

?>