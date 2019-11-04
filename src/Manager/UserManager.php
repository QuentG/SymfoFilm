<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	/**
	 * UserManager constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 */
	public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->entityManager = $entityManager;
		$this->passwordEncoder = $passwordEncoder;
	}

	/**
	 * @param string $email
	 * @param string $username
	 * @param string $password
	 * @param array $role
	 */
	public function create(string $email, string $username, string $password, array $role)
	{
		$user = new User();

		$user->setEmail($email)
			->setUsername($username)
			->setPassword($this->passwordEncoder->encodePassword($user, $password))
			->setRoles($role);

		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

}