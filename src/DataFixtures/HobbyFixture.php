<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Hobby;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Yoga",
            "Lectura",
            "Videojuegos",
            "Aprender una lengua",
            "Tocar la guitarra",
            "Meditación",
            "Ver a Xokas",
            "Bailar",
            "Programar",
            "Gimnasio"
        ];
        for ($i=0; $i<count($data);$i++) {
            $hobby = new Hobby();
            $hobby->setDesignation($data[$i]);
            $manager->persist($hobby);
        }
        $manager->flush();
    }
    
}
