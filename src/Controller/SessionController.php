<?php

namespace App\Controller;

use App\Entity\Day;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Validator\Constraints\Date;

class SessionController extends AbstractController
{
    private $em;
    private $repo;
    const DATE_TIMESTAMP = 86400;

    public function __construct(EntityManagerInterface $em, SessionRepository $repo){
        $this->em = $em;
        $this->repo = $repo;
    }

    /**
     * @Route("/session", name="app_session", methods="GET|POST")
     */
    public function index(Request $req): Response
    {        
        $latestSession = $this->repo->findOneBy([], ['id' => 'DESC']);
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
            'events' => $events
        ]);
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

            $dateDiff = date_diff($session->getStart(), $session->getUntil())->d;
            if ($dateDiff < 100){
                // Exception
            }

            $startDateTimestamp = $session->getStart()->getTimestamp();
            for ($i = 0; $i <= $dateDiff; $i++) {
                $dateTimestamp = date('Y-m-d', $startDateTimestamp + 86400 * $i);
                $date = new DateTimeImmutable($dateTimestamp);

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
