<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewRentalsController extends AbstractController
{
    /**
     * @Route("/view/rentals/{id}", name="view_rentals")
     */
    public function displayAction($id): Response
    {
        $rentals = $this->getDoctrine()
            ->getRepository('App:Rental')
            ->find($id);

        return $this->render('view_rentals/index.html.twig', array('data' => $rentals));

    }
}
