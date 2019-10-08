<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * SecurityController constructor
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * @Route("/login", name="app_login")
	 *
	 * @param AuthenticationUtils $authenticationUtils
	 * @return Response
	 */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
            $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
        	'last_username' => $lastUsername,
			'error' => $error !== null
		]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

	/**
	 * @Route("/registration", name="register")
	 *
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @return Response
	 */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
	{
		$user = new User();

		$form = $this->createForm(RegistrationType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$hash = $passwordEncoder->encodePassword($user, $user->getPassword());
			$user->setPassword($hash);

			$this->manager->persist($user);
			$this->manager->flush();

			$this->addFlash('success', 'Votre compte a été crée avec succès !');

			return $this->redirectToRoute('app_login');
		}

		return $this->render('security/register.html.twig', [
			'form' => $form->createView()
		]);
	}
}
