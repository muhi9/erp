<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MakeController extends AbstractController
{
    #[Route('/make', name: 'app_make')]
    public function index(): Response
    {
        return $this->render('make/index.html.twig', [
            'controller_name' => 'MakeController',
        ]);
    }
}
