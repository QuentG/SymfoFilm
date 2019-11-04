<?php

namespace App\Command;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'create:user';

	private $userManager;

    public function __construct(UserManager $userManager, string $name = null)
	{
		$this->userManager = $userManager;
		parent::__construct($name);
	}

	protected function configure()
    {
        $this->setDescription('Create a new User')
			->addArgument('Email', InputArgument::REQUIRED)
			->addArgument('Username', InputArgument::REQUIRED)
			->addArgument('Password', InputArgument::REQUIRED)
			->addArgument('Role', InputArgument::IS_ARRAY, 'ROLE_USER OR ROLE_ADMIN', ['ROLE_USER']);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Start creation...');

        $this->userManager->create(
        	$input->getArgument('Email'),
			$input->getArgument('Username'),
			$input->getArgument('Password'),
			$input->getArgument('Role')
		);

        $io->success('User created ✅');
    }
}
