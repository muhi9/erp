<?php

namespace DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AircraftBundle\Entity\Aircraft;
use LicensesBundle\Entity\License;
use LicensesBundle\Entity\LicenseLPC;
use LicensesBundle\Entity\MedicalLicense;
use LicensesBundle\Entity\LicenseOPC;
use LicensesBundle\Entity\LicenseEndorsement;
use FileBundle\Entity\File;

class DefaultController extends Controller {
    use \BaseBundle\Controller\DataGridControllerTrait;


	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction() {
	
		return $this->render('DashboardBundle:Default:index.html.twig');
	}


}
