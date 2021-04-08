<?php

namespace App\Controller;

use App\Entity\Attribution;
use App\Form\AttributionType;
use App\Repository\AttributionRepository;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AttributionController extends AbstractController
{
    private $em;
    private $attributionRepo;
    private $sessionRepo;

    public function __construct(EntityManagerInterface $em, AttributionRepository $attributionRepo, SessionRepository $sessionRepo) 
    {
        $this->em = $em;
        $this->attributionRepo = $attributionRepo;
        $this->sessionRepo = $sessionRepo;
    }

    /**
     * @Route("/attribution", name="app_attribution", methods="GET|POST")
     */
    public function index(Request $req): Response
    {
        $attribution = new Attribution;
        $form = $this->createForm(AttributionType::class, $attribution);
        $formView = $form->createView();
        $form->handleRequest($req);

        $latestSession = $this->sessionRepo->findOneBy([], ['id' => 'DESC']);

        // Error handler : If no session
        if (is_null($latestSession)) {
            return $this->redirectToRoute('app_session');
        }

        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_DIR' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }

        $attributionListDoctrine = $this->attributionRepo->findBy(['session' => $latestSession]);
        // sort by professor name

        $attributionList = [];
        // 6 is higher semester
        for ($i = 1; $i <= 6; $i++) {
            foreach ($attributionListDoctrine as $value) {
                if ($value->getSubject()->getSemester() == $i) {
                    array_push($attributionList, $value);
                }
            }
        }
        

        
        
        
        
        
        if ($form->isSubmitted() && $form->isValid()){
        // Create new attribution with form
            if (is_null($attribution->getCmAmount()) && is_null($attribution->getTdAmount()) && is_null($attribution->getTpAmount())){
                $this->addFlash('danger', 'Vous n\'avez attribué aucun cours.');
                return $this->redirectToRoute('app_attribution');
            }

            // If attribution already exists
            $data = $req->request->get('attribution');
            if ($this->attributionRepo->findOneBy([
                'user' => $data['user'],
                'subject' => $data['subject'],
                'session' => $latestSession
            ])) {
                // delete it
                $oldAttribution = $this->attributionRepo->findOneBy([
                    'user' => $data['user'],
                    'subject' => $data['subject'],
                    'session' => $latestSession
                ]);
                
                $this->em->remove($oldAttribution);

                $this->addFlash('success', 'L\'attribution de ' . $attribution->getUser() . ' dans le module de ' . $attribution->getSubject() . ' a été mise à jour !');

            } else {
                $this->addFlash('success', 'Votre attribution a été ajoutée !');

            }

            $attribution->setSession($latestSession);
            $this->em->persist($attribution);
            $this->em->flush();

            return $this->redirectToRoute('app_attribution');
        }

        return $this->render('attribution/index.html.twig', [
            'attributionForm' => $formView,
            'attributionList' => $attributionList,
            'session' => $latestSession
        ]);
    }



    /**
     * @Route("/attribution/{id<\d+>}/delete", name="app_attribution_delete", methods="DELETE")
     */
    public function delete(Attribution $attribution, Request $req): Response
    {   
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_DIR' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }


        // CSRF Validation
        if ($this->isCsrfTokenValid('app_attribution_delete' . $attribution->getId(), $req->request->get('_token'))) {
            // Remove user
            $this->em->remove($attribution);
            $this->em->flush();
        }

        $this->addFlash('success', 'L\'attribution de ' . $attribution->getUser() .' en ' . $attribution->getSubject() . ' a été supprimé !');
        return $this->redirectToRoute('app_attribution');

    }
}
