<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Persona;
use App\Event\AddPersonaEvent;
use App\Event\ListAllPersonasEvent;
use App\Form\PersonaType;
use App\Service\Helpers;
use App\Service\MailerService;
use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/persona')]
//#[IsGranted('ROLE_USER')]

class PersonaController extends AbstractController
{
   
    public function __construct(
        private EventDispatcherInterface $dispatcher
    ){}

    #[Route('/', name: 'persona.list')]
    public function index(ManagerRegistry $doctrine) : Response{
        $repository = $doctrine->getRepository(Persona::class);

        $personas = $repository->findAll();

        return $this->render('persona/index.html.twig', [
            'personas' => $personas
        ]);
    }

    #[Route('/alls/age/{ageMin}/{ageMax}', name: 'persona.list.age')]
    public function personasByAge(ManagerRegistry $doctrine, $ageMin, $ageMax) : Response{        

        $repository = $doctrine->getRepository(Persona::class);

        $personas = $repository->findPersonaByAgeInterval($ageMin, $ageMax);    
        
        return $this->render('persona/index.html.twig', [
            'personas' => $personas
        ]);
    }

    #[Route('/stats/age/{ageMin}/{ageMax}', name: 'persona.list.age')]
    public function statsPersonasByAge(ManagerRegistry $doctrine, $ageMin, $ageMax) : Response{
        $repository = $doctrine->getRepository(Persona::class);

        $stats = $repository->statsPersonaByAgeInterval($ageMin, $ageMax);      

        return $this->render('persona/stats.html.twig', [
            'stats'  => $stats[0],
            'ageMin' => $ageMin,
            'ageMax' => $ageMax
        ]);
    }
    
    
    #[Route('/alls/{page?1}/{nbre?12}', name: 'persona.list.alls')]
    //#[IsGranted("ROLE_USER")]
    
    public function indexAlls(ManagerRegistry $doctrine, $page, $nbre) : Response{
        $repository = $doctrine->getRepository(Persona::class);
        
        $nbPersona = $repository->count([]);
        $nbrePage = ceil($nbPersona / $nbre);

        $personas = $repository->findBy([], ['age' => 'ASC'], $nbre, ($page -1) * $nbre);
        $listAllPersonasEvent = new ListAllPersonasEvent(count($personas));
        $this->dispatcher->dispatch($listAllPersonasEvent, ListAllPersonasEvent::LIST_ALL_PERSONA_EVENT);

        return $this->render('persona/index.html.twig', [
            'personas' => $personas,
            'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }

    #[Route('/{id<\d+>}', name: 'persona.detail')]
    public function detail(ManagerRegistry $doctrine, $id) : Response{
        $repository = $doctrine->getRepository(Persona::class);

        $persona = $repository->find($id);      

        if(!$persona) {
            $this->addFlash('error', "La persona con id $id no existe");
            return $this->redirectToRoute('persona.list');
        }

        return $this->render('persona/detail.html.twig', [
            'persona' => $persona            
        ]);
    }

    #[Route('/edit/{id?0}', name: 'persona.edit')]
    public function addPersona(Persona $persona = null, ManagerRegistry $doctrine, Request $request, UploadService $uploadService): Response
    {
        /*
        $em = $doctrine->getManager();

        $persona2 = new Persona();
        $persona2->setFirstname('Jiménez');
        $persona2->setName('Óscar');
        $persona2->setAge(25);
        $persona2->setJob('Albañil');

        $persona3 = new Persona();
        $persona3->setFirstname('Dominguez');
        $persona3->setName('Pedro');
        $persona3->setAge(44);
        $persona3->setJob('Médico');
       
        $em->persist($persona2);
        $em->persist($persona3);
        */   

        //$this->denyAccessUnlessGranted("ROLE_ADMIN");
        $new = false;

        if(!$persona){
            $new = true;
            $persona = new Persona();
        }

        $form =  $this->createForm(PersonaType::class, $persona);
        $form->remove('createdAt');
        $form->remove('updatedAt');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //Para subir la imagen al formulario

            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $directory = $this->getParameter('persona_directory');

                $persona->setImage($uploadService->uploadFile($photo, $directory));                
            }    
            //Fin parte imagen 
            
            if($new){
                $message = ' ha sido creada correctamente';
                $persona->setCreatedBy($this->getUser());
            }else{
                $message = ' ha sido actualizado con éxito';
            }

            $em = $doctrine->getManager();
            $em->persist($persona);
            $em->flush();

            //Parte Event
            if($new){
                $addPersonaEvent = new AddPersonaEvent($persona);

                $this->dispatcher->dispatch($addPersonaEvent, AddPersonaEvent::ADD_PERSONA_EVENT);
            }

            //Fin parte Event

            $this->addFlash('success', $persona->getName() . $message);
            return $this->redirectToRoute('persona.list');

        }else{
            return $this->render('persona/add-persona.html.twig', [
                     'form' => $form->createView()
            ]);        

    }
        
    }
    #[Route('/delete{id}', name: 'persona.delete')]
    //#[IsGranted("ROLE_ADMIN")]
    public function deletePersona(ManagerRegistry $doctrine, Persona $persona = null): RedirectResponse
    {        
        if($persona){
            $em = $doctrine->getManager();
            $em->remove($persona);
            $em->flush();

            $this->addFlash('success', 'La persona ha sido eliminada con éxito');
        }else{
            $this->addFlash('error', 'Persona inexistente');
        }

        return $this->redirectToRoute('persona.list.alls');
    }

    #[Route('/update/{id}/{name}/{firstname}/{age}', name: 'persona.update')]
    public function updatePersona(ManagerRegistry $doctrine, $id, $name, $firstname, $age): RedirectResponse
    {      
        $repository = $doctrine->getRepository(Persona::class);

        $persona = $repository->find($id); 

        if($persona){
            $em = $doctrine->getManager();
            $persona->setName($name);
            $persona->setFirstname($firstname);
            $persona->setAge($age);
            $em->persist($persona);
            
            $em->flush();

            $this->addFlash('success', 'La persona ha sido actualizada con éxito');
        }else{
            $this->addFlash('error', 'Persona inexistente');
        }

        return $this->redirectToRoute('persona.list.alls');
    }
}
