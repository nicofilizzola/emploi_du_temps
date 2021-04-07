<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Form\EquipmentType;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquipmentController extends AbstractController
{
    private $equipmentRepo;
    private $em;


    public function __construct(EquipmentRepository $equipmentRepo, EntityManagerInterface $em) {
        $this->equipmentRepo = $equipmentRepo;
        $this->em = $em;
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
     * @Route("/equipment/request", name="app_equipment_request")
     */
    public function request(): Response
    {
        return $this->render('equipment/index.html.twig', [
            'controller_name' => 'EquipmentController',
        ]);
    }


    /**
     * @Route("/equipment/request/create", name="app_equipment_request_create")
     */
    public function createRequest(): Response
    {
        return $this->render('equipment/index.html.twig', [
            'controller_name' => 'EquipmentController',
        ]);
    }
}