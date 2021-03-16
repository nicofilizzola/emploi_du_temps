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
     * @Route("/preference", name="app_preference", methods="GET")
     */
    public function index(Request $req): Response
    {
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
            'preferences' => $preferences,
            'unavailabilities' => $unavailabilities,
        ]);
    }


    /**
     * @Route("/preference/create", name="app_preference_create", methods="POST")
     */
    public function create(Request $req): Response
    {
        $preference = new Preference;
        $data = $req->request;
        $weekdays =
        $timeRanges =

        // if (isset($data['preference_weekday_1'])) {
            
        // }
        // if (isset($data['preference_weekday_2'])) {

        // }
        

        $this->redirectToRoute('app_preference');
    }
}
