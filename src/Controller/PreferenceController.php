<?php

namespace App\Controller;

use App\Entity\Preference;
use App\Form\PreferenceType;
use App\Repository\SessionRepository;
use App\Repository\PreferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AttributionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PreferenceController extends AbstractController
{
    private $em;
    private $preferenceRepo;
    private $attributionRepo;
    private $sessionRepo;

    public function __construct(EntityManagerInterface $em, AttributionRepository $attributionRepo, SessionRepository $sessionRepo, PreferenceRepository $preferenceRepo) 
    {
        $this->em = $em;
        $this->attributionRepo = $attributionRepo;
        $this->sessionRepo = $sessionRepo;
        $this->preferenceRepo = $preferenceRepo;
    }
    
    /**
     * @Route("/preference", name="app_preference")
     */
    public function index(Request $req): Response
    {
        $preference = new Preference;
        $form = $this->createForm(PreferenceType::class, $preference);
        $formView = $form->createView();
        $form->handleRequest($req);

        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
        $preferences = $this->preferenceRepo->findBy([
            'state' => true,
            // 'user' => ,
            // 'session' => latestSession
        ]);
        $unavailabilities = $this->preferenceRepo->findBy([
            'state' => false,
            // 'user' => ,
            // 'session' => latestSession
        ]);

        return $this->render('preference/index.html.twig', [
            'preferenceForm' => $formView,
            'preferences' => $preferences,
            'unavailabilities' => $unavailabilities,
        ]);
    }
}
