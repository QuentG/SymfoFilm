<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\People;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @Route("/", name="home")
	 *
	 * @param MovieRepository $movieRepository
	 * @return Response
	 */
    public function index(MovieRepository $movieRepository) : Response
    {
        return $this->render('home/index.html.twig', [
        	'lastMovies' =>  $movieRepository->findLatestMovies()
		]);
    }

	/**
	 * @param CategoryRepository $categoryRepository
	 * @return Response
	 */
    public function navBar(CategoryRepository $categoryRepository)
	{
		$categories = $categoryRepository->findBy([], ['title' => 'ASC']); // OrderBy title ASC

		return $this->render('_includes/header.html.twig', [
			'categories' => $categories,
			'user' => $this->getUser()
		]);
	}

	/**
	 * @Route("/people/{slug}", name="people")
	 *
	 * @param People $people
	 * @return Response
	 */
	public function people(People $people)
	{
		return $this->render('home/people.html.twig', [
			'people' => $people
		]);
	}

	/**
	 * @Route("/category/{slug}", name="category")
	 *
	 * @param Category $category
	 * @return Response
	 */
    public function category(Category $category)
	{
		return $this->render('home/category.html.twig', [
			'category' => $category
		]);
	}
}
