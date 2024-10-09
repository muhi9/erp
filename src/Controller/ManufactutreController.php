<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManufactutreController extends AbstractController
{
    #[Route('/manufactutre', name: 'app_manufactutre')]
    public function index(): Response
    {
        return $this->render('manufactutre/index.html.twig', [
            'controller_name' => 'ManufactutreController',
        ]);
    }
}
