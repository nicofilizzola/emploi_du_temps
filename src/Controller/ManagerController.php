<?php

namespace App\Controller;

use App\Controller\Traits\Calendar;
use App\Repository\AttributionRepository;
use App\Repository\DayRepository;
use App\Repository\EventRepository;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
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

    public function __construct(SessionRepository $sessionRepo, DayRepository $dayRepo, EventRepository $eventRepo, UserRepository $userRepo, AttributionRepository $attributionRepo) {
        $this->sessionRepo = $sessionRepo;
        $this->dayRepo = $dayRepo;
        $this->eventRepo = $eventRepo;
        $this->userRepo = $userRepo;
        $this->attributionRepo = $attributionRepo;
    }

    /**
     * @Route("/manager", name="app_manager")
     */
    public function index(): Response
    {
        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
        $days = $this->dayRepo->findBy(['session' => $latestSession->getId()], ['id' => 'ASC']);
        $monthsDays = $this->getMonthsDays($days);
        $events = $this->eventRepo->findAll();

        $currentAttributions = $this->attributionRepo->findBy(['session' => $latestSession]);
        



        return $this->render('manager/index.html.twig', [
            'session' => $latestSession,
            'monthsDays' => $monthsDays,
            'events' => $events,
            'attributions' => $currentAttributions
        ]);
    }
}
