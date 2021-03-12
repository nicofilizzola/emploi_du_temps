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
     * @Route("/attribution", name="app_attribution")
     */
    public function index(Request $req): Response
    {
        $attribution = new Attribution;
        $form = $this->createForm(AttributionType::class, $attribution);
        $formView = $form->createView();
        $form->handleRequest($req);

        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
        $attributionList = $this->attributionRepo->findBy(['session' => $latestSession]);


        return $this->render('attribution/index.html.twig', [
            'attributionForm' => $formView,
            'attributionList' => $attributionList
        ]);
    }

    /**
     * @Route("/attribution/create", name="app_attribution_create" methods="POST")
     */
    /*public function create(Request $req): Response
    {
        $attribution = new Attribution;
        $form = $this->createForm(AttributionType::class, $attribution);
        $formView = $form->createView();
        $form->handleRequest($req);

        return $this->render('attribution/index.html.twig', [
            'attributionForm' => $formView,
        ]);
    }*/
}
