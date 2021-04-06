<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Error handler : If user has no access or isn't connected
        if (!$this->getUser() || !in_array('ROLE_MAN' , $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_home');
        }


        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRole = $request->request->get('registration_form')['higherRole'];

            $allRoles = [
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

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $this->addFlash('success', 'l\'utilisateur ' . $user->getUsername() . ' a été ajouté avec succès !');
            return $this->redirectToRoute('app_user');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
