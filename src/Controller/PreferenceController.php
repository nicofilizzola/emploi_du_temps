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
    public function index(): Response
    {
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_PRO' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }





        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);
        $preferences = $this->preferenceRepo->findBy([
            'state' => true,
            'user' => $this->getUser(),
            'session' => $latestSession
        ]);
        $unavailabilities = $this->preferenceRepo->findBy([
            'state' => false,
            'user' => $this->getUser(),
            'session' => $latestSession
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

        // Get current user's attributions
        $userAttributions = $this->attributionRepo->findBy([
            'session' => $latestSession,
            'user' => $this->getUser()
        ]);

        // dd($unavailabilities);

        return $this->render('preference/index.html.twig', [
            'preferences' => $preferences,
            'unavailabilities' => $unavailabilities,
            'session' => $latestSession,
            'weeks' => $weeks,
            'userAttributions' => $userAttributions
        ]);
    }

    private function getPreferenceString($preference) {
        $weekString = "";
        // All weeks
        if ($preference->getStartWeek == 100) {
            $weekString = 'Toute l\'année ';

            // Except
            if (!is_null($preference->getExceptStartWeek)) {
                $weekString += '(sauf ';

                // One week except
                if (is_null($preference->getExceptEndWeek)) {
                    $weekString += 'la semaine ' . strval($preference->getExceptStartWeek);

                // Multiple week except
                } else {
                    $weekString += 'de la semaine ' . strval($preference->getExceptStartWeek) . 'jusqu\'à la semaine ' .  strval($preference->getExceptEndWeek);
                }
            
                $weekString += ') ';
            }
        } else {
            // Single week
            if (is_null($preference->getEndWeek)) {
                $weekString = 'La semaine ' . strval($preference->getStartWeek);

            // Multiple weeks
            } else {
                $weekString = 'De la semaine ' . strval($preference->getStartWeek) . ' à la semaine ' . strval($preference->getEndWeek);

                // Except
                if (!is_null($preference->getExceptStartWeek)) {
                    $weekString += '(sauf ';

                    // One week except
                    if (is_null($preference->getExceptEndWeek)) {
                        $weekString += 'la semaine ' . strval($preference->getExceptStartWeek);

                    // Multiple week except
                    } else {
                        $weekString += 'de la semaine ' . strval($preference->getExceptStartWeek) . 'jusqu\'à la semaine ' .  strval($preference->getExceptEndWeek);
                    }
                
                    $weekString += ') ';
                }
            }
        }

        return $weekString;
    }






    /**
     * @Route("/preference/create", name="app_preference_create", methods="POST")
     */
    public function create(Request $req): Response
    {
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_PRO' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }


        


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

        // set selected weekdays
        $preferenceWeekdays = [];
        for ($i = 1; $i <= 5; $i++) {
            if (!is_null($data->get('preference_weekday_' . $i))){
                array_push($preferenceWeekdays, $i);

            }
        }

        // set selected times
        $preferenceTimes = [];
        for ($i = 1; $i <= 7; $i++) {
            if (!is_null($data->get('preference_time_' . $i))){
                array_push($preferenceTimes, $i);

            }
        }

        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);

        // set startWeek, endWeek, exceptStartWeek, exceptEndWeek
        $startWeek = $data->get('preference_week');
        if ($startWeek == 'all') {
            // Week 100 means all weeks
            $startWeek = 100;
        }
        $isEndWeek = $data->get('is_preference_endweek');
        $endWeek = $data->get('preference_endweek');
        $isExceptWeek = $data->get('is_preference_exceptweek');
        $exceptStartWeek = $data->get('preference_exceptweek');
        $isExceptEndWeek = $data->get('is_preference_exceptendweek');
        $exceptEndWeek = $data->get('preference_exceptendweek');
        $preferenceNote = $data->get('preference_note');
        





        // Error handler: if no (first or only) week selected
        if ($startWeek == '') {
            $this->addFlash('warning', 'Vous n\'avez pas sélectionné la semaine ou les semaines concernées.');
            return $this->redirectToRoute('app_preference');
        }
        // Error handler: is_endweek checked but no week selected
        if (!is_null($isEndWeek) && $endWeek == '') {
            $this->addFlash('warning', 'Vous n\'avez pas indiqué la semaine de fin.');
            return $this->redirectToRoute('app_preference');
        }
        // Error handler: is_except checked but no week selected
        if ($isExceptWeek == 1 && $exceptStartWeek == '') {
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
            empty($preferenceWeekdays) || 
            empty($preferenceTimes) || 
            strlen($preferenceNote) > 300 || 
            !isset($preferenceState) || 
            !is_null($isExceptWeek) && !is_null($isExceptEndWeek) && intval($exceptStartWeek) > intval($exceptEndWeek) ||
            $startWeek == 'all' && !is_null($isEndWeek) ||
            $startWeek == 'all' && !is_null($endWeek)

        ) {
            $this->addFlash('danger', 'Votre requête est invalide.');
            return $this->redirectToRoute('app_preference');
        }




        // Instanciate object
        $preference = new Preference;
        $preference->setSession($latestSession);
        $preference->setState($preferenceState);
        $preference->setNote($preferenceNote);
        $preference->setUser($this->getUser());
        $preference->setStartWeek($startWeek);
        $preference->setEndWeek($endWeek);
        $preference->setExceptStartWeek($exceptStartWeek);
        $preference->setExceptEndWeek($exceptEndWeek);
        $preference->setWeekdays($preferenceWeekdays);
        $preference->setTimes($preferenceTimes);
       
        $this->em->persist($preference);
        $this->em->flush();
    
        $this->addFlash('success', 'Votre préférence a été ajoutée avec succès !');
        return $this->redirectToRoute('app_preference');
        
        
    }


    /**
     * @Route("/preference/{id<\d+>}/delete", name="app_preference_delete", methods="DELETE")
     */
    public function delete(Preference $preference, Request $req): Response
    {
        // CSRF Validation
        if ($this->isCsrfTokenValid('app_preference_delete' . $preference->getId(), $req->request->get('_token'))) {
            // Delete preference
            $this->em->remove($preference);
            $this->em->flush();

            $this->addFlash('success', 'Votre préférence a été supprimée.');
            return $this->redirectToRoute('app_preference');
        }
    }   


    /**
     * @Route("/preference/delete/all", name="app_preference_delete_all", methods="DELETE")
     */
    public function deleteAll(Request $req): Response
    {
        // CSRF Validation
        if ($this->isCsrfTokenValid('app_preference_delete_all', $req->request->get('_token'))) {
            
            // Delete all preferences
            $prefState = $req->request->get('preferenceState');
            if ($prefState == 'preference') {
                $preferences = $this->preferenceRepo->findBy(['state' => 1]);

                $this->addFlash('success', 'Toutes vos disponibilités ont été supprimées !');

            } else if ($prefState == 'unavailability') {
                $preferences = $this->preferenceRepo->findBy(['state' => 0]);

                $this->addFlash('success', 'Toutes vos indisponibilités ont été supprimées !');

            }
            
            foreach ($preferences as $value) {
                $this->em->remove($value);
            }
            $this->em->flush();

            return $this->redirectToRoute('app_preference');
        }
    }  
}





