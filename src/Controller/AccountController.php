<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/my-account", name="my_account")
	 *
	 * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        return $this->render('security/account.html.twig', [
        	'user' => $this->getUser()
        ]);
    }
}
