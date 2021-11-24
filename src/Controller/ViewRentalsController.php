<?php

namespace App\Controller;

use App\Entity\Rental;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewRentalsController extends AbstractController
{
    /**
     * @Route("/view/rentals", name="view_rentals")
     */
    public function displayAction(): Response
    {
        $rentals = $this->getDoctrine()
            ->getRepository('App:Rental')
            ->findAll();

        return $this->render('view_rentals/index.html.twig', array('data' => $rentals));

    }
}