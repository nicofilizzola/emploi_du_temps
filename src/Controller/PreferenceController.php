<?php

namespace App\Controller;

use App\Entity\Preference;
use App\Form\PreferenceType;
use App\Repository\SessionRepository;
use App\Repository\PreferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AttributionRepository;
use App\Repository\DayRepository;
use DateTime;
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
    private $dayRepo;

    public function __construct(EntityManagerInterface $em, AttributionRepository $attributionRepo, SessionRepository $sessionRepo, PreferenceRepository $preferenceRepo, DayRepository $dayRepo) 
    {
        $this->em = $em;
        $this->attributionRepo = $attributionRepo;
        $this->sessionRepo = $sessionRepo;
        $this->preferenceRepo = $preferenceRepo;
        $this->dayRepo = $dayRepo;
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
        
        $days = $this->dayRepo->findBy([
            'session' => $latestSession
        ]);
        $weeks = [];
        $today = new DateTime();

        foreach ($days as $value) {
            $timestamp = $value->getDate()->getTimestamp();
            // get monday and filter only days after today
            if (date('w', $timestamp) == 1 && $timestamp > $today->getTimestamp()) {
                $endOfWeek = date('d-m-Y', strtotime('+6 days', $timestamp));
                $week = [
                    'start' => date('d-m-Y', $timestamp),
                    'end' => $endOfWeek
                ];
                array_push($weeks, $week);
            }
        }

        return $this->render('preference/index.html.twig', [
            'preferences' => $preferences,
            'unavailabilities' => $unavailabilities,
            'session' => $latestSession,
            'weeks' => $weeks
        ]);
    }


    /**
     * @Route("/preference/create", name="app_preference_create", methods="POST")
     */
    public function create(Request $req): Response
    {
        $data = $req->request;

        // set state
        $inputPreferenceState = $data->get('preference_state');
        if ($inputPreferenceState == 1 || $inputPreferenceState ==0) {
            if ($data->get('preference_state') == 1) {
                $preferenceState = 1;
                // $preference->setState(1);
            } else if ($data->get('preference_state') == 0) {
                $preferenceState = 0;
                // $preference->setState(0);
            }
        }

        // get selected weekdays
        $preferenceWeekdays = [];
        for ($i = 1; $i <= 5; $i++) {
            if (!is_null($data->get('preference_weekday_' . $i))){
                $preferenceWeekdays[$i - 1] = true;
            } else {
                $preferenceWeekdays[$i - 1] = false;
            }
        }

        // get selected times
        $preferenceTimes = [
            ['8:00:00', false],
            ['9:30:00', false],
            ['11:00:00', false],
            ['12:30:00', false],
            ['13:30:00', false],
            ['15:00:00', false],
            ['16:30:00', false],
            ['18:00:00', false],
        ];
        for ($i = 1; $i <= 7; $i++) {
            if (!is_null($data->get('preference_time_' . $i))){
                $preferenceTimes[$i - 1][1] = true;
            }
        }

        // Error handler: count note chars
        $preferenceNote = $data->get('preference_note');
        if (strlen($preferenceNote) > 300) {
            // error here: too many characters
        }



        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
        $days = $this->dayRepo->findBy([
            'session' => $latestSession
        ]);
        $firstWeek = $data->get('preference_week');
        $isEndWeek = $data->get('is_preference_endweek');
        $endWeek = $data->get('preference_endweek');


        // if all weeks selected and nothing else sent (weekwise)
        if ($firstWeek == 'all' && is_null($isEndWeek) && is_null($endWeek)) {
            foreach ($days as $day) {

                // proceed only if not weekend and if current day has been selected (check in $preferenceWeekdays)
                $timestamp = $day->getDate()->getTimestamp();
                $weekdayIndex = date('w', $timestamp);
                if ($weekdayIndex != 0 && $weekdayIndex != 6 && $preferenceWeekdays[intval($weekdayIndex) - 1]) {

                    // proceed only if time selected (check $preferenceTimes)
                    foreach ($preferenceTimes as $preferenceTime) {
                        if ($preferenceTime[1]) {
                            $preference = new Preference;
                            $preference->setState($preferenceState);
                            $preference->setDatetime(new DateTime(date('Y-m-d', $timestamp) . "T" . $preferenceTime[0])); 
                            $preference->setNote($preferenceNote);
                            $preference->setSession($latestSession);

                            $this->em->persist($preference);
                        }
                    } 
                }
            }

            $this->em->flush();



            

        } /*elseif ($firstweek == nigga) {

        }*/



        

        return $this->redirectToRoute('app_preference');
    }
}
