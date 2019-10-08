<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\People;
use App\Entity\Rating;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	/**
	 * AppFixtures constructor.
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 */
	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	public function load(ObjectManager $manager)
    {
    	// Use Faker with FR language
    	$faker = Factory::create('fr_FR');

    	// Categories
    	$categories = ['Comédie', 'Drame', 'Action', 'Aventure', 'Policier'];
    	$categoriesTab = [];

    	for ($i = 0; $i < 5; $i++) {
    		$category = new Category();
    		$category->setTitle($categories[$i]);
    		$manager->persist($category);

    		$categoriesTab[] = $category;
		}

    	// Actors
        $actors = [];
    	for ($p = 0; $p < 20; $p++) {
    		$people = new People();
    		$people->setLastName($faker->lastName)
				->setFirstName($faker->firstNameMale)
				->setDescription($faker->sentence)
				->setPicture('https://randomuser.me/api/portraits/men/' . $p . ".jpg");

    		$manager->persist($people);
    		$actors[] = $people;
		}

    	// Users
    	$users = [];
    	for ($u = 0; $u < 10; $u++) {
    		$user = new User();
    		$user->setEmail($faker->email)
				->setUsername($faker->userName)
				->setPicture('https://randomuser.me/api/portraits/men/' . $u . ".jpg");

    		$hash = $this->passwordEncoder->encodePassword($user, 'password');
    		$user->setPassword($hash);

    		$manager->persist($user);
    		$users[] = $user;
		}

    	// Admin user
		$userAdmin = new User();
    	$userAdmin->setUsername('QuentG')
			->setEmail('contact@quentingans.fr')
			->setRoles(['ROLE_ADMIN'])
			->setPicture('https://avatars1.githubusercontent.com/u/33687186?s=400&u=6b3285d05a257728cdeb26b9137b8964a217dfd9&v=4');

    	$hash = $this->passwordEncoder->encodePassword($userAdmin, 'password');
		$userAdmin->setPassword($hash);

		$manager->persist($userAdmin);

		// Movies
//		$movies = [];
		for ($m = 0; $m <= 30; $m++) {
			$movie = new Movie();
			$movie->setTitle($faker->realText(20))
				->setImage('')
				->setReleasedAt($faker->dateTimeBetween('-30 years'))
				->setSynopsis('<p>' . join('</p><p>', $faker->paragraphs(10)) . '</p>');

			$movieActors = $faker->randomElements($actors, $faker->numberBetween(2, 14));
			foreach ($movieActors as $actor) {
				$movie->addActor($actor);
			}

			$movieCategories = $faker->randomElements($categoriesTab, $faker->numberBetween(1, 4));
			foreach ($movieCategories as $category) {
				$movie->addCategory($category);
			}

			// Ratings
			for ($r = 0; $r < $faker->numberBetween(0, 20); $r++) {
				$rating = new Rating();
				$rating->setAuthor($faker->randomElement($users))
					->setMovie($movie)
					->setNotation($faker->numberBetween(1, 5))
					->setCreatedAt($faker->dateTimeBetween('- 15 years'));

				if ($faker->numberBetween(1, 10) > 1) {
					$rating->setComment($faker->text);
				}

				$manager->persist($rating);
			}

			$manager->persist($movie);
//			$movies[] = $movie;
		}

        $manager->flush();
    }
}
