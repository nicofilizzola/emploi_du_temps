<?php

namespace App\Controller;

use App\Entity\Attribution;
use App\Form\AttributionType;
use App\Repository\AttributionRepository;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AttributionController extends AbstractController
{
    private $em;
    private $attributionRepo;
    private $sessionRepo;

    public function __construct(EntityManagerInterface $em, AttributionRepository $attributionRepo, SessionRepository $sessionRepo) 
    {
        $this->em = $em;
        $this->attributionRepo = $attributionRepo;
        $this->sessionRepo = $sessionRepo;
    }

    /**
     * @Route("/attribution", name="app_attribution", methods="GET|POST")
     */
    public function index(Request $req): Response
    {
        $attribution = new Attribution;
        $form = $this->createForm(AttributionType::class, $attribution);
        $formView = $form->createView();
        $form->handleRequest($req);

        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);

        // If no session
        if (is_null($latestSession)) {
            return $this->redirectToRoute('app_session');
        }

        $attributionList = $this->attributionRepo->findBy(['session' => $latestSession]);
        // sort by professor name

        if ($form->isSubmitted() && $form->isValid()){
        // Create new attribution with form
            if (is_null($attribution->getCmAmount()) && is_null($attribution->getTdAmount()) && is_null($attribution->getTpAmount())){
                $this->addFlash('danger', 'Vous n\'avez attribué aucun cours.');
                return $this->redirectToRoute('app_attribution');
            }

            $attribution->setSession($latestSession);
            $this->em->persist($attribution);
            $this->em->flush();

            $this->addFlash('success', 'Votre attribution a été ajoutée.');
            return $this->redirectToRoute('app_attribution');
        }

        return $this->render('attribution/index.html.twig', [
            'attributionForm' => $formView,
            'attributionList' => $attributionList,
            'session' => $latestSession
        ]);
    }
}
