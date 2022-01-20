<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{

    #[Route('/template', name: 'template')]
    public function template(){
        return $this->render('template.html.twig');
    }

    #[Route('/order/{maVar}', name: 'test.order.route')]
    public function testOrderRoute($maVar){
        return new Response(
            "<html><body>$maVar</body></html>"            
        );
    }


    #[Route('/first', name: 'first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'name' => 'Pedro',
            'firstname' => 'Sanchez'
        ]);
    }

    //#[Route('/sayHello/{name}/{firstname}', name: 'say.hello')]
    public function sayHello(Request $request, $name, $firstname): Response
    {
        //dd($request);
        
        return $this->render('first/hello.html.twig', [
            'name' => $name,
            'firstname' => $firstname
        ]);
    }

    #[Route('/multi/{entero1<\d+>}/{entero2<\d+>}', name: 'multiplication')]
    public function multiplication($entero1, $entero2){
        $resultado = $entero1 * $entero2;

        return new Response("<h1>$resultado</h1>");
    }
}
