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
        if ($inputPreferenceState == 1 || $inputPreferenceState == 0) {
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
        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
        $days = $this->dayRepo->findBy([
            'session' => $latestSession
        ]);
        $firstWeek = $data->get('preference_week');
        $isEndWeek = $data->get('is_preference_endweek');
        $endWeek = $data->get('preference_endweek');
        $isExceptWeek = $data->get('is_preference_exceptweek');
        $exceptWeek = $data->get('preference_exceptweek');
        $isExceptEndWeek = $data->get('is_preference_exceptendweek');
        $exceptEndWeek = $data->get('preference_exceptendweek');
        $preferenceNote = $data->get('preference_note');
        
        // Error handler: if no (first or only) week selected
        if ($firstWeek == '') {
            $this->addFlash('warning', 'Vous n\'avez pas sélectionné la semaine ou les semaines concernées.');
            return $this->redirectToRoute('app_preference');
        }
        // Error handler: is_endweek checked but no week selected
        if (!is_null($isEndWeek) && $endWeek == '') {
            $this->addFlash('warning', 'Vous n\'avez pas indiqué la semaine de fin.');
            return $this->redirectToRoute('app_preference');
        }
        // Error handler: is_except checked but no week selected
        if ($isExceptWeek == 1 && $exceptWeek == '') {
            $this->addFlash('warning', 'Vous n\'avez pas indiqué la semaine d\'exception.');
            return $this->redirectToRoute('app_preference'); 
        }
        // Error handler: is_except checked but no week selected
        if ($isExceptEndWeek == 1 && $exceptEndWeek == '') {
            $this->addFlash('warning', 'Vous n\'avez pas indiqué la semaine de fini de l\'exception.');
            return $this->redirectToRoute('app_preference'); 
        }
        // Error handler: if client side validation breached:
            // no times selected
            // no weekdays selected 
            // note length too long 
            // preferenceState not selected
            // except and exceptCheck checked and except week higher than exceptEnd 
            // 'all' week selected in start but endweek checkbox checked
            // 'all' week selected in start but endweek value selected

            //

        if (
            $this->preferenceTimesNoneSelected($preferenceTimes) || 
            strlen($preferenceNote) > 300 || 
            !in_array(true, $preferenceWeekdays) || 
            !isset($preferenceState) || 
            !is_null($isExceptWeek) && !is_null($isExceptEndWeek) && intval($exceptWeek) > intval($exceptEndWeek) ||
            $firstWeek == 'all' && !is_null($isEndWeek) ||
            $firstWeek == 'all' && !is_null($endWeek)

        ) {
            $this->addFlash('danger', 'Votre requête est invalide.');
            return $this->redirectToRoute('app_preference');
        }


        $weekIndex = 0;

        // startWeek: 'all' selected
        if ($firstWeek == 'all') {
            foreach ($days as $value) {
                
                // proceed only if not weekend and if current day has been selected (check in $preferenceWeekdays)
                $timestamp = $value->getDate()->getTimestamp();
                $weekdayIndex = date('w', $timestamp);

                $weekIndex = $this->countWeeks($weekdayIndex, $weekIndex);
                
                // Check if except checkbox was checked and a value was selected
                if ($isExceptWeek == 1 && !is_null($exceptWeek)) {

                    // Continue if exceptEnd not selected
                    if (is_null($isExceptEndWeek) && is_null($exceptEndWeek)) {

                        // Only persist if the current week hasa value different from except
                        if ($weekIndex !== intval($exceptWeek)) {
                            $this->createPreference($preferenceState, $timestamp, $preferenceTimes, $preferenceNote, $latestSession, $weekdayIndex, $preferenceWeekdays);

                        }
                    
                    // Continue if exceptEnd selected
                    } elseif ($isExceptEndWeek == 1 && !is_null($exceptEndWeek)) {
                        // Only persist if the current week hasa value different from except range
                        if ($weekIndex < $exceptWeek || $weekIndex > $exceptEndWeek) {
                            $this->createPreference($preferenceState, $timestamp, $preferenceTimes, $preferenceNote, $latestSession, $weekdayIndex, $preferenceWeekdays);
    
                        }
                    }
                    
                } else {
                    $this->createPreference($preferenceState, $timestamp, $preferenceTimes, $preferenceNote, $latestSession, $weekdayIndex, $preferenceWeekdays);

                }
            }
        }


        // startWeek: 'all' not selected
        if ($firstWeek !== 'all') {

            // No endWeek selected
            if (is_null($isEndWeek) && is_null($endWeek)) {
                foreach ($days as $day) {

                    $timestamp = $day->getDate()->getTimestamp();
                    $weekdayIndex = date('w', $timestamp);
    
                    $weekIndex = $this->countWeeks($weekdayIndex, $weekIndex);
                
                    // if loop's week matches selected week
                    if ($weekIndex == intval($firstWeek)) {
                        $this->createPreference($preferenceState, $timestamp, $preferenceTimes, $preferenceNote, $latestSession, $weekdayIndex, $preferenceWeekdays);
                    } 
                }
            
                
            // endWeek selected
            } else if (!is_null($isEndWeek) && !is_null($endWeek)) {
                $weekIndex = 0;
                foreach ($days as $day) {
    
                    // proceed only if not weekend and if current day has been selected (check in $preferenceWeekdays)
                    $timestamp = $day->getDate()->getTimestamp();
                    $weekdayIndex = date('w', $timestamp);
    
                    $weekIndex = $this->countWeeks($weekdayIndex, $weekIndex);
    
                    // if loop's week matches selected week
                    if ($weekIndex >= intval($firstWeek) && $weekIndex <= intval($endWeek)) {
                        $this->createPreference($preferenceState, $timestamp, $preferenceTimes, $preferenceNote, $latestSession, $weekdayIndex, $preferenceWeekdays);
                        
                    } 
                }
            }
        }
    

        $this->em->flush();
    
        $this->addFlash('success', 'Votre préférence a été ajoutée avec succès !');
        return $this->redirectToRoute('app_preference');
        
        
    }



















    




    // functions required for routes
    public function createPreference($preferenceState, $preferenceDayTimestamp, $preferenceTimes, $preferenceNote, $preferenceSession, $weekdayIndex, $preferenceWeekdays) {
        // proceed only if not weekend and selected weekday selected
        if ($weekdayIndex != 0 && $weekdayIndex != 6 && $preferenceWeekdays[intval($weekdayIndex) - 1]) {
            foreach ($preferenceTimes as $preferenceTime) {
                if ($preferenceTime[1]) {
                    $preference = new Preference;
                    $preference->setState($preferenceState);
                    $preference->setDatetime(new DateTime(date('Y-m-d', $preferenceDayTimestamp) . "T" . $preferenceTime[0])); 
                    $preference->setNote($preferenceNote);
                    $preference->setSession($preferenceSession);

                    // only persist if the same preference hasn't already been created
                    if (!$this->preferenceRepo->findOneBy([
                        'datetime' => $preference->getDatetime()/*,
                        user */
                    ])) {
                        $this->em->persist($preference);
                    }

                    
                }
            } 
        }
    }

    public function preferenceTimesNoneSelected($preferenceTimes) {
        foreach ($preferenceTimes as $element) {
            if ($element[1]) {
                return false;
            }
        }
        return true;
    }

    public function countWeeks($weekdayIndex, $weekIndex) {
        // count weeks
        if ($weekdayIndex == 1){
            $weekIndex++;
        } 
        return $weekIndex;
    }

}
