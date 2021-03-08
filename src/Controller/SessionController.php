<?php

namespace App\Controller;

use App\Entity\Day;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Repository\DayRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Validator\Constraints\Date;

class SessionController extends AbstractController
{
    private $em;
    private $repo;
    const MIN_CLASS_DAYS = 200;

    public function __construct(EntityManagerInterface $em, SessionRepository $repo, DayRepository $dayRepo){
        $this->em = $em;
        $this->repo = $repo;
        $this->dayRepo = $dayRepo;
    }

    /**
     * @Route("/session", name="app_session", methods="GET|POST")
     */
    public function index(Request $req): Response
    {        
        if ($this->repo->findAll()){
            $latestSession = $this->repo->findOneBy([], ['id' => 'DESC']);
            $days = $this->dayRepo->findBy(['session' => $latestSession->getId()]);
            $events = [
                'Cours',
                'Vacances',
                'Vacances (Zone C)',
                'PTUT',
                'Stage 1A',
                'Stage 2A',
                'Stage 3A',
                'Conseil de l\'IUT',
                'Conseil de direction de l\'IUT',
                'Conseil Départamental',
                'Jurys semestre'
            ];
    
            return $this->render('session/index.html.twig', [
                'session' => $latestSession,
                'days' => $days,
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
            $this->em->persist($session);
            $this->em->flush();

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
            return $this->redirectToRoute('app_session_create');
        }
        return $this->render('session/create.html.twig', [
            'sessionForm' => $formView,
        ]);
    }
}
