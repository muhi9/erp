<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OEMController extends AbstractController
{
    #[Route('/o/e/m', name: 'app_o_e_m')]
    public function index(): Response
    {
        return $this->render('oem/index.html.twig', [
            'controller_name' => 'OEMController',
        ]);
    }
}
