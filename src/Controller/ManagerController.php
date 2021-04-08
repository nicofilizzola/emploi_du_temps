<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DayRepository;
use App\Repository\UserRepository;
use App\Controller\Traits\Calendar;
use App\Repository\EventRepository;
use App\Repository\SessionRepository;
use App\Repository\AttributionRepository;
use App\Repository\EquipmentRequestRepository;
use App\Repository\PreferenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagerController extends AbstractController
{
    use Calendar;

    private $sessionRepo;
    private $dayRepo;
    private $eventRepo;
    private $userRepo;
    private $attributionRepo;
    private $preferenceRepo;
    private $equipmentRequestRepo;

    public function __construct(SessionRepository $sessionRepo, DayRepository $dayRepo, EventRepository $eventRepo, UserRepository $userRepo, AttributionRepository $attributionRepo, PreferenceRepository $preferenceRepo, EquipmentRequestRepository $equipmentRequestRepo) {
        $this->sessionRepo = $sessionRepo;
        $this->dayRepo = $dayRepo;
        $this->eventRepo = $eventRepo;
        $this->userRepo = $userRepo;
        $this->attributionRepo = $attributionRepo;
        $this->preferenceRepo = $preferenceRepo;
        $this->equipmentRequestRepo = $equipmentRequestRepo;
    }

    /**
     * @Route("/manager", name="app_manager")
     */
    public function index(): Response
    {
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_MAN' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }



        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
        if (is_null($latestSession)) {
            $this->addFlash('warning', 'L\'espace gestionnaire EDT est indisponible car l\'année scolaire n\'a pas encore été créée...');
            return $this->redirectToRoute('app_session');
        }

        $days = $this->dayRepo->findBy(['session' => $latestSession->getId()], ['id' => 'ASC']);
        $currentAttributions = $this->attributionRepo->findBy(['session' => $latestSession]);
        
        // Get all professors ids within currentAttributions
        $professorIds = [];
        foreach ($currentAttributions as $value) {
            if (!in_array($value->getUser()->getId(), $professorIds)) {
                array_push($professorIds, $value->getUser()->getId());
            };
        }



        return $this->render('manager/index.html.twig', [
            'session' => $latestSession,
            'monthsDays' => $this->getMonthsDays($days),
            'events' => $this->eventRepo->findAll(),
            'professors' => $this->userRepo->findBy(['id' => $professorIds]),
            'equipmentRequests' => $this->equipmentRequestRepo->findBy(['session' => $latestSession])
        ]);
    }








    /**
     * @Route("/user/{id<\d+>}", name="app_user_view", methods="GET")
     */
    public function view(User $user, EquipmentRequestRepository $equipmentRequestRepo): Response
    {   
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_MAN' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }

        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
        $userAttributions = $this->attributionRepo->findBy([
            'session' => $latestSession,
            'user' => $user
        ]);

        // Error handler: if user has no attributions for current session
        if (empty($userAttributions)) {
            return $this->redirectToRoute('app_home');
        }



        return $this->render('user/view.html.twig', [
            'user' => $user,
            'session' => $latestSession,
            'attributions' => $userAttributions,
            'preferences' => $this->preferenceRepo->findBy([
                'session' => $latestSession,
                'user' => $user,
                'state' => true
            ]),
            'unavailabilities' => $this->preferenceRepo->findBy([
                'session' => $latestSession,
                'user' => $user,
                'state' => false
            ]),
            'equipmentRequests' => $equipmentRequestRepo->findBy([
                'user' => $user,
                'session' => $latestSession
            ])
        ]);
    }
}
