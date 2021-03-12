<?php

namespace App\Controller;

use App\Entity\Attribution;
use App\Form\AttributionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AttributionController extends AbstractController
{
    /**
     * @Route("/attribution", name="app_attribution")
     */
    public function index(Request $req): Response
    {
        $attribution = new Attribution;
        $form = $this->createForm(AttributionType::class, $attribution);
        $formView = $form->createView();
        $form->handleRequest($req);

        return $this->render('attribution/index.html.twig', [
            'attributionForm' => $formView,
        ]);
    }

    /**
     * @Route("/attribution/create", name="app_attribution_create" methods="POST")
     */
    /*public function create(Request $req): Response
    {
        $attribution = new Attribution;
        $form = $this->createForm(AttributionType::class, $attribution);
        $formView = $form->createView();
        $form->handleRequest($req);

        return $this->render('attribution/index.html.twig', [
            'attributionForm' => $formView,
        ]);
    }*/
}
