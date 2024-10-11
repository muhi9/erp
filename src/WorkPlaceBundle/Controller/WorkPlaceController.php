<?php

namespace WorkPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/workPlace")
 */
class WorkPlaceController extends Controller
{
    public function indexAction()
    {
        return $this->render('WorkPlaceBundle:Default:index.html.twig');
    }
}
