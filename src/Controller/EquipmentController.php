<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Form\EquipmentType;
use App\Entity\EquipmentRequest;
use App\Repository\SessionRepository;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AttributionRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EquipmentRequestRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquipmentController extends AbstractController
{
    private $equipmentRepo;
    private $em;
    private $sessionRepo;
    private $equipmentRequestRepo;
    private $attributionRepo;
    private $subjectRepo;


    public function __construct(EquipmentRepository $equipmentRepo, EntityManagerInterface $em, SessionRepository $sessionRepo, EquipmentRequestRepository $equipmentRequestRepo, AttributionRepository $attributionRepo, SubjectRepository $subjectRepo) {
        $this->equipmentRepo = $equipmentRepo;
        $this->em = $em;
        $this->sessionRepo = $sessionRepo;
        $this->equipmentRequestRepo = $equipmentRequestRepo;
        $this->attributionRepo = $attributionRepo;
        $this->subjectRepo = $subjectRepo;
    }

    /**
     * @Route("/equipment", name="app_equipment", methods="GET|POST")
     */
    public function index(Request $req): Response
    {
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_MAN' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }


        // Get all equipments by category
        $equipments = $this->equipmentRepo->findBy([], ['category' => 'ASC']); 

        // Create form
        $equipment = new Equipment;
        $form = $this->createForm(EquipmentType::class, $equipment);
        $formView = $form->createView();
        $form->handleRequest($req);

        // ON FORM SUBMITTED
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($equipment);
            $this->em->flush();

            $this->addFlash('success', $equipment->getName() . ' a été ajouté à la liste de matériel !');
            return $this->redirectToRoute('app_equipment');
        } 

        return $this->render('equipment/index.html.twig', [
            'equipments' => $equipments,
            'equipmentsForm' => $formView
        ]);
    }



    /**
     * @Route("/equipment/{id<\d+>}/delete", name="app_equipment_delete", methods="DELETE")
     */
    public function delete(Request $req, Equipment $equipment): Response
    {
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_MAN' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }


        // CSRF Validation
        if ($this->isCsrfTokenValid('app_equipment_delete' . $equipment->getId(), $req->request->get('_token'))) {
            // Remove equipment
            $this->em->remove($equipment);
            $this->em->flush();
        }

        $this->addFlash('success', $equipment->getName() . ' a été supprimé de la liste de matériel !');
        return $this->redirectToRoute('app_equipment');

    }




    /**
     * @Route("/equipment/request", name="app_equipment_request", methods="GET")
     */
    public function request(): Response
    {
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_PRO' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }



        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);

        $userEquipmentReqs = $this->equipmentRequestRepo->findBy([
            'user' => $this->getUser(),
            'session' => $latestSession
        ]);

        $userAttributions = $this->attributionRepo->findBy([
            'session' => $latestSession,
            'user' => $this->getUser()
        ]);

        return $this->render('equipment/request.html.twig', [
            'equipmentReqs' => $userEquipmentReqs,
            'latestSession' => $latestSession,
            'userAttributions' => $userAttributions
        ]);
    }


    /**
     * @Route("/equipment/request/create", name="app_equipment_request_create", methods="GET|POST")
     */
    public function createRequest(Request $req): Response
    {
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_MAN' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }


        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);

        $userAttributions = $this->attributionRepo->findBy([
            'session' => $latestSession,
            'user' => $this->getUser()
        ]);

        // Error handler : If user has no attributions for the current session
        if (empty($userAttributions)) {
            return $this->redirectToRoute('app_home');
        }


        $equipments = $this->equipmentRepo->findBy([], ['category' => 'ASC']);

        
        // If form sent
        if ($req->isMethod('POST')) {

            $data = $req->request;
            // Error handler: Client side validation breached
                // subject input null
                // equipment input null
                // TO DO: user checked class different from chosen subject
            if (
                is_null($data->get('subject')) || 
                is_null($data->get('equipment'))
            ) {
                $this->addFlash('danger', 'Votre requête est invalide');
                return $this->redirectToRoute('app_equipment_request_create');

            }

            // Error handler: No classes selected
            $checkedAmount = 0;
            foreach ($data as $key => $value) {
                if (str_contains($key, 'subject-')) {
                    $checkedAmount++;
                }
            }
            if ($checkedAmount <= 0) {

                $this->addFlash('danger', 'Vous n\'avez choisi aucun cours pour votre demande de matériel.');
                return $this->redirectToRoute('app_equipment_request_create');
            }

            // Error handler: Note not filled
            if (is_null($data->get('note'))) {

                $this->addFlash('danger', 'Vous n\'avez pas justifié votre demande (Voir le dernier champ).');
                return $this->redirectToRoute('app_equipment_request_create');
            }

            // set tds and tps
            $checkboxesChecked = [];
            $td = [];
            $tp = [];
            foreach ($data as $key => $value) {
                if (str_contains($key, 'subject-')) {
                    array_push($checkboxesChecked, $key);
                }
            }
            
            foreach ($checkboxesChecked as $key) {
                // get only checkbox data from the selected subject
                // example: value="subject-5-td-3"
                $checkboxValue = explode('-', $key);

                if ($checkboxValue[1] == $data->get('subject')) {
                    if ($checkboxValue[2] == 'td') {
                        array_push($td, $checkboxValue[3]);

                    } else if ($checkboxValue[2] == 'tp') {
                        array_push($tp, $checkboxValue[3]);

                    }
                }
            }

            // Instance class
            $equipmentRequest = new EquipmentRequest;
            $equipmentRequest->setSession($latestSession);
            $equipmentRequest->setUser($this->getUser());
            $equipmentRequest->setEquipment($this->equipmentRepo->findOneBy(['id' => $data->get('equipment')]));
            $equipmentRequest->setSubject($this->subjectRepo->findOneBy(['id' => $data->get('subject')]));
            $equipmentRequest->setTds($td);
            $equipmentRequest->setTps($tp);
            $equipmentRequest->setNote($data->get('note'));

            

            $this->em->persist($equipmentRequest);
            $this->em->flush();

            $this->addFlash('success', 'Votre demande de matériel a étée envoyée !');
            return $this->redirectToRoute('app_equipment_request');

        }


        return $this->render('equipment/createRequest.html.twig', [
            'userAttributions' => $userAttributions,
            'equipments' => $equipments
        ]);
    }






        /**
     * @Route("/equipment/request/{id<\d+>}/delete", name="app_equipment_request_delete", methods="DELETE")
     */
    public function deleteRequest(EquipmentRequest $equipmentRequest, Request $req): Response
    {   
        // Error handler: If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_PRO' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }

        // Error handler: If equipmentRequest doesn't belong to the user
        if ($this->getUser() !== $equipmentRequest->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // CSRF Validation
        if ($this->isCsrfTokenValid('app_equipment_request_delete' . $equipmentRequest->getId(), $req->request->get('_token'))) {
            // Remove user
            $this->em->remove($equipmentRequest);
            $this->em->flush();
        }

        $this->addFlash('success', 'Votre demande de matériel a été supprimée !');
        return $this->redirectToRoute('app_equipment_request');

    }
}
