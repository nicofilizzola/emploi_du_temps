<?php

namespace App\Controller;

use App\Entity\Day;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Repository\DayRepository;
use App\Repository\EventRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
// use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Symfony\Component\Validator\Constraints\Date;

class SessionController extends AbstractController
{
    private $em;
    private $sessionRepo;
    private $dayRepo;
    private $eventRepo;

    const MIN_CLASS_DAYS = 200;

    public function __construct(EntityManagerInterface $em, SessionRepository $sessionRepo, DayRepository $dayRepo, EventRepository $eventRepo){
        $this->em = $em;
        $this->sessionRepo = $sessionRepo;
        $this->dayRepo = $dayRepo;
        $this->eventRepo = $eventRepo;
    }

    public function getMonthsDays($days){
        $monthsDays = [
            // 12 months max
            [],[],[],[],[],[],[],[],[],[],[],[],[]
        ];
        $i = 0;
        foreach ($days as $key => $day){
            $dayMonth = date('Ym', $day->getDate()->getTimestamp());
            
            if (isset($prevDayMonth) && $dayMonth > $prevDayMonth){
                $i++;
            }
            
            array_push($monthsDays[$i], $day); 
            $prevDayMonth = $dayMonth;
        };
        return $monthsDays;
    }

    /**
     * @Route("/session", name="app_session", methods="GET|POST")
     */
    public function index(Request $req): Response
    {        
        if ($req->isMethod('POST')){
            foreach ($req->request as $key => $value){
                //dd($key . " => " . $value);
                $dayEventCharacters = explode('-', $key);
                $dayInput = intval($dayEventCharacters[1]);
                $eventInput = intval($dayEventCharacters[3]);
                
                $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
                $days = $this->dayRepo->findBy(['session' => $latestSession->getId()], ['id' => 'ASC']);
                $dayIndex = 1;
                foreach ($days as $day){
                    if (date('w', $day->getDate()->getTimestamp()) != 6 && date('w', $day->getDate()->getTimestamp()) != 0) {
                        if ($dayIndex == $dayInput) {
                            $event = $this->eventRepo->findOneBy(['id' => $eventInput]);
                            if ($event){
                                $day->addEvent($event);
                                $this->em->persist($event);
                            }
                        }
                        $dayIndex++;
                    }
                }
            }
            $this->em->flush();
            $this->redirectToRoute('app_session');
        }
        
        if ($this->sessionRepo->findAll()){
            $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
            $days = $this->dayRepo->findBy(['session' => $latestSession->getId()], ['id' => 'ASC']);
            $monthsDays = $this->getMonthsDays($days);
            $events = $this->eventRepo->findAll();
            
            return $this->render('session/index.html.twig', [
                'session' => $latestSession,
                'monthsDays' => $monthsDays,
                'events' => $events
            ]);
        }
        return $this->render('session/index.html.twig');
    }

    /**
     * @Route("/session/create", name="app_session_create", methods="GET|POST")
     */
    public function create(Request $req): Response
    {
        $session = new Session;
        $form = $this->createForm(SessionType::class, $session);
        $formView = $form->createView();
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()){
            $startDate = $session->getStart();
            $untilDate = $session->getUntil();
            $dateDiff = date_diff($startDate, $untilDate)->days;
            $startDateTimestamp = $startDate->getTimestamp();
            $untilDateTimestamp = $untilDate->getTimestamp();

            if ($dateDiff < $this::MIN_CLASS_DAYS || $startDateTimestamp >= $untilDateTimestamp){
                // add exception if selected year is not a precedent year (if untildate('y') is repeated)
                $this->addFlash('danger', 'La période que vous avez choisie est invalide.');
                return $this->redirectToRoute('app_session_create');
            }
            if ($dateDiff > 365){
                $this->addFlash('danger', 'Une session ne peut pas avoir une durée supérieure à un (1) an.');
                return $this->redirectToRoute('app_session_create');
            }
            /*if (date('w', $startDateTimestamp) != 0){
                $this->addFlash('warning', 'La date de début doit être un dimanche.');
                return $this->redirectToRoute('app_session_create');
            }*/
            

            $this->em->persist($session);
            $this->em->flush();

            for ($i = 0; $i <= $dateDiff; $i++) {
                $dateSet = date('Y-m-d', $startDateTimestamp + 60 * 60 * 24 * $i);
                $date = new DateTimeImmutable($dateSet);

                $day = new Day;
                $day->setDate($date);
                $day->setSession($session);

                $this->em->persist($day);
                $this->em->flush();
            }
            $this->addFlash('success', 'Session créée avec succès !');
            return $this->redirectToRoute('app_session');
        }
        return $this->render('session/create.html.twig', [
            'sessionForm' => $formView,
        ]);
    }
}
