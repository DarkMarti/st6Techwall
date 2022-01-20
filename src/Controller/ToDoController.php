<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/todo")]
class ToDoController extends AbstractController
{
    //#[Route('/todo', name: 'todo')]

    /**
     * @param Request $request
     * @Route("/", name="todo")
     */

    public function index(Request $request): Response
    {
        /*
        $todos = array(
            'uno' => 'elemento uno',
            'dos' => 'elemento dos',
            "tres" => 'elemento tres'
        );

        return $this->render('to_do/index.html.twig', [
            'todos' => $todos
        ]);
        */

        //Otra forma de hacer lo mismo es meterlo a una sesión
        $todos = array();

        $session = $request->getSession();
        if(!$session->has('todos')){
            $todos = array(
                'uno' => 'elemento uno',
                'dos' => 'elemento dos',
                "tres" => 'elemento tres'
            );
        

        $session->set('todos', $todos);
        $this->addFlash('info', 'Lista completa de todos');

        }
        return $this->render('todo/index.html.twig', [
            'todos' => $todos
        ]);

    }

    #[Route('/add/{name}/{content}', name: 'todo.add', defaults: [ 'name' => 'cacahuete', 'content' => 'st6'])]
    public function addTodo(Request $request, $name, $content):RedirectResponse{
        $session = $request->getSession();

        if($session->has('todos')){
            $todos = $session->get('todos');
            if(isset($todos[$name])){
                $this->addFlash('error', "Lista con nombre $name existe");
            }else{
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Añadido correctamente");                
            }

        }else{
            $this->addFlash('info', 'Lista completa de todos no se encuentra');
        }

        return $this->redirectToRoute('todo');
    }   


    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content):RedirectResponse{
        $session = $request->getSession();

        if($session->has('todos')){
            $todos = $session->get('todos');
            if(!isset($todos[$name])){
                $this->addFlash('error', "Lista con nombre $name no existe");
            }else{
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Editado correctamente");                
            }

        }else{
            $this->addFlash('info', 'Lista completa de todos no se encuentra');
        }

        return $this->redirectToRoute('todo');
    } 


    
    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name):RedirectResponse{
        $session = $request->getSession();

        if($session->has('todos')){
            $todos = $session->get('todos');
            if(!isset($todos[$name])){
                $this->addFlash('error', "Lista con nombre $name no existe");
            }else{
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "Eliminado correctamente");                
            }

        }else{
            $this->addFlash('info', 'Lista completa de todos no se encuentra');
        }

        return $this->redirectToRoute('todo');
    } 


    #[Route('/reset', name: 'todo.reset')]
    public function resetTodo(Request $request):RedirectResponse{
        $session = $request->getSession();

        $session->remove('todos');

        return $this->redirectToRoute('todo');
    }
}
