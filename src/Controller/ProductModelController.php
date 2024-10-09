<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductModelController extends AbstractController
{
    #[Route('/product/model', name: 'app_product_model')]
    public function index(): Response
    {
        return $this->render('product_model/index.html.twig', [
            'controller_name' => 'ProductModelController',
        ]);
    }
}
