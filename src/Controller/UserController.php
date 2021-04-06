<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $userRepo;
    private $em;

    public function __construct(UserRepository $userRepo, EntityManagerInterface $em)
    {
        $this->userRepo = $userRepo;
        $this->em = $em;
    }

    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {
        $users = $this->userRepo->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/{id<\d+>}/edit", name="app_user_edit", methods="GET|POST")
     */
    public function edit(User $user, Request $req, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        //$user = new User;
        $form = $this->createForm(UserType::class, $user);
        $formView = $form->createView();
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRole = $req->request->get('user')['higherRole'];

            $allRoles = [
                null,
                'PRO',
                'DIR',
                'MAN',
                'ADM'
            ];

            // Error handler : If input role code invalid
            if (!array_key_exists($userRole, $allRoles)) {
                $this->addFlash('danger', 'Votre requête est invalide');
                return $this->redirectToRoute('app_register');
            }

            // assign user roles
            $userRoles = [];
            $loopIndex = 1;
            foreach ($allRoles as $element) {
                if ($loopIndex <= intval($userRole)) {
                    array_push($userRoles, 'ROLE_' . $element);
                }
                $loopIndex++;
            }
            $user->setRoles($userRoles);

            // if new password entered
            $plainPassword = $req->request->get('user')['plainPassword'];
            if ($plainPassword['first'] !== '' && $plainPassword['second'] !== '') {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $this->addFlash('success', 'l\'utilisateur ' . $user->getUsername() . ' a bien été modifié !');
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'editUserForm' => $formView
        ]);
    }



    /**
     * @Route("/user/{id<\d+>}/delete", name="app_user_delete", methods="DELETE")
     */
    public function delete(User $user, Request $req, UserPasswordEncoderInterface $passwordEncoder): Response
    {   
        // Error handler : If deleted user is active user,
        if ($this->getUser() == $user) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer votre propre compte !');
            return $this->redirectToRoute('app_user');
        } 
           
        // Remove user
        $this->em->remove($user);
        $this->em->flush();

        $this->addFlash('success', 'L\'utilisateur ' . $user->getUsername() . ' a été supprimé !');
        return $this->redirectToRoute('app_user');

    }
}
