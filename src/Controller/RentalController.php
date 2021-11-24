<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Rental;
use App\Entity\User;
use App\Form\RentalType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentalController extends AbstractController
{
    /**
     * @Route("/rental", name="rental")
     */
    public function newRental(Request $request): Response
    {
        $id = $request->get('id');
        $movie = $this->getDoctrine()
            ->getRepository(Movie::class)
            ->find($id);

        $rental = new Rental();

        $form = $this->createForm(RentalType::class, $rental);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var User $user */
            $user = $this->getUser();

            $rental->setUser($user);

            $rental->setMovie($movie);
            $em->persist($rental);
            $em->flush();

            $this->addFlash('success', 'Movie Rented Successfully!');
        }

        return $this->render('rental/index.html.twig', array(
            'RentalForm' => $form->createView(),
            'movie' => $movie
        ));
    }
}
