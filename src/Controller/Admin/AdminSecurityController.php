<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminSecurityController extends AbstractController
{

	/**
	 * @Route("/admin/login", name="admin_login")
	 *
	 * @param AuthenticationUtils $authenticationUtils
	 * @return Response
	 */
	public function login(AuthenticationUtils $authenticationUtils)
	{
		$lastUsername = $authenticationUtils->getLastUsername();
		$error = $authenticationUtils->getLastAuthenticationError();

		return $this->render('admin/security/login.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error !== null
 		]);
	}

	/**
	 * @Route("/admin/logout", name="admin_logout")
	 */
	public function logout() {}
}