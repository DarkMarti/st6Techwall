<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/tab")]
class TabController extends AbstractController
{   
    /*
    #[Route('/{nb?5<\d+>}', name: 'tab')]
    public function index($nb): Response
    {
        $notes = [];
        for($i = 0; $i<$nb; $i++){
            $notes[] = rand(0,5);
        }

        return $this->render('tab/index.html.twig', [
            'notes' => $notes
        ]);
    }
    */

    #[Route('/users', name: 'tab.users')]
    public function users(): Response
    {
        $users = [
          ['nombre' => 'Pablo', 'apellidos' => 'Divorciado', 'edad' => 43],
          ['nombre' => 'Pedro', 'apellidos' => 'Picapiedra', 'edad' => 35],
          ['nombre' => 'Juan' , 'apellidos' => 'Bocachancla','edad' => 54]
        ];

        return $this->render('tab/users.html.twig', [
            'users' => $users
        ]);
    }
}
