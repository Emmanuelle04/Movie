<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;



class MovieController extends AbstractController
{
    /**
     * @Route("/movie", name="movie")
     */

    public function new(Request $request)
    {
        $movie = new Movie();

        $form  = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $movie->setAvailability(0);

            $em       = $this->getDoctrine()->getManager();
            $file     = $form->get('poster')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('poster_directory'), $fileName);
            $movie->setPoster($fileName);

            $em->persist($movie);
            $em->flush();

            $this->addFlash('success', 'Movie Created!');
            header("refresh:3;url=view/movie");
        }

        return $this->render('movie/index.html.twig', array(
            'MovieForm' => $form->createView(),
        ));
    }

    /**
     * @Route("/movie/edit/{id}", name="edit")
     */

    public function edit(Request $request, $id): Response
    {
        $movie = $this->getDoctrine()->getRepository(Movie::class)->find($id);

        if (!$movie) {
            $this->addFlash('error', 'Movie not found at Id '.$id);
        }

        $form  = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file     = $form->get('poster')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('poster_directory'), $fileName);
            $movie->setPoster($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $this->addFlash('success', 'Movie Edited!');
            header("refresh:3;url=../../view/movie");
        }

        return $this->render('movie/editmovie.html.twig', array(
            'MovieForm' => $form->createView(),
        ));
    }

    /**
     * @Route("/movie/delete/{id}", name="delete")
     */

    public function delete(Request $request, $id): Response
    {
        $movie = $this->getDoctrine()->getRepository(Movie::class)->find($id);

        if (!$movie) {
            $this->addFlash('error', 'Movie not found at Id '.$id);
        } else
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movie);
            $em->flush();

            $this->addFlash('success', 'Movie Deleted!');
//            header("refresh:3;url=../../view/movie");
        }

        return $this->redirectToRoute('view_movie');
    }

}

