<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubjectController extends AbstractController
{
    private $subjectRepo;
    private $em;

    public function __construct(SubjectRepository $subjectRepo, EntityManagerInterface $em) {
        $this->subjectRepo = $subjectRepo;
        $this->em = $em;
    }

    /**
     * @Route("/subject", name="app_subject")
     */
    public function index(): Response
    {
        return $this->render('subject/index.html.twig', [
            'subjects' => $this->subjectRepo->findBy([], ['semester' => 'ASC'])
        ]);
    }



    /**
     * @Route("/subject/create", name="app_subject_create", methods="GET|POST")
     */
    public function create(Request $req): Response
    {
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_DIR' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }



        $subject = new Subject;
        $form = $this->createForm(SubjectType::class, $subject);
        $formView = $form->createView();
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            // dd(strlen($subject->getPPN()));
            if (strlen($subject->getPPN()) < 2 || strlen($subject->getPPN() > 5)) {
                $this->addFlash('warning', 'Le PPN ou abbréviation que vous avez renseigné ne contient pas entre 2 et 5 caractères.');
                return $this->redirectToRoute('app_subject_create');
                dd(strlen($subject->getPPN()) < 2);
            } else {
                dd(strlen($subject->getPPN() > 5));

            }

            $this->em->persist($subject);
            $this->em->flush();

            $this->addFlash('success', 'La matière ' . $subject . ' a été ajoutée !');
            return $this->redirectToRoute('app_subject');
        }

        return $this->render('subject/create.html.twig', [
            'subjectForm' => $formView
        ]);
    }




    // /**
    //  * @Route("/subject/{id</d+>}/delete", name="app_subject_delete", methods="DELETE")
    //  */
    // public function delete(Subject $subject, Request $req): Response
    // {
    //     // Error handler : If user has no access or isn't connected
    //     if (!$this->getUser() || !in_array('ROLE_DIR' , $this->getUser()->getRoles())) {
    //         return $this->redirectToRoute('app_home');
    //     }

    //     // CSRF Validation
    //     if ($this->isCsrfTokenValid('app_subject_delete' . $subject->getId(), $req->request->get('_token'))) {
    //         // Remove user
    //         $this->em->remove($subject);
    //         $this->em->flush();
    //     }

    //     $this->addFlash('success', 'La matière ' . $subject . ' a été supprimé !');
    //     return $this->redirectToRoute('app_user');
    // }
}
