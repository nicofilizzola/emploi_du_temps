<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_session_create", methods="GET|POST")
     */
    public function create(Request $req): Response
    {
        $session = new Session;
        $form = $this->createForm(SessionType::class, $session);
        $formView = $form->createView();
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($session);
            $this->em->flush();

            $this->addFlash('success', 'Session créée avec succès !');
            return $this->redirectToRoute('app_session_create');
        }

        return $this->render('session/index.html.twig', [
            'sessionForm' => $formView,
        ]);
    }
}
