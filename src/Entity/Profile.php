<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimeStampTrait;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\HasLifecycleCallbacks()]
                  
class Profile
{
    use TimeStampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $url;

    #[ORM\Column(type: 'string', length: 50)]
    private $rs;

    #[ORM\OneToOne(mappedBy: 'Profile', targetEntity: Persona::class, cascade: ['persist', 'remove'])]
    private $persona;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getRs(): ?string
    {
        return $this->rs;
    }

    public function setRs(string $rs): self
    {
        $this->rs = $rs;

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): self
    {
        // unset the owning side of the relation if necessary
        if ($persona === null && $this->persona !== null) {
            $this->persona->setProfile(null);
        }

        // set the owning side of the relation if necessary
        if ($persona !== null && $persona->getProfile() !== $this) {
            $persona->setProfile($this);
        }

        $this->persona = $persona;

        return $this;
    }

    public function __toString(): string
    {
        return $this->rs. "" . $this->url;        
    }
}
