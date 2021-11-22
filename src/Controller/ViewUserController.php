<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewUserController extends AbstractController
{
    /**
     * @Route("/view/user", name="view_user")
     */
    public function index(): Response
    {
        return $this->render('view_user/index.html.twig', [
            'controller_name' => 'ViewUserController',
        ]);
    }
}
