<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\MovieRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
	private $manager;

	public function __construct(ObjectManager $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * @Route("/movies", name="movies")
	 *
	 * @param MovieRepository $movieRepository
	 * @return Response
	 */
    public function index(MovieRepository $movieRepository)
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->findAll()
        ]);
    }

	/**
	 * @Route("/movie/{slug}", name="movie_show")
	 *
	 * @param Movie $movie
	 * @param Request $request
	 * @return RedirectResponse|Response
	 */
    public function show(Movie $movie, Request $request)
	{
		$user = $this->getUser();

		if ($user) {
			$rating = new Rating();
			$rating->setMovie($movie)
				->setAuthor($user);

			$form = $this->createForm(RatingType::class, $rating);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$this->manager->persist($rating);
				$this->manager->flush();

				return $this->redirectToRoute('movie_show', ['slug' => $movie->getSlug()]);
			}
		}

		return $this->render('movie/movie.html.twig', [
			'movie' => $movie,
			'form' => isset($form) ? $form->createView() : false,
			'user' => $user,
			'alreadyHasRating' => $user && $user->hasRate($movie)
		]);
	}
}
