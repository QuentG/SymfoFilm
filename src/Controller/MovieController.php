<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{

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
	 * @return RedirectResponse|Response
	 */
    public function show(Movie $movie)
	{
		$user = $this->getUser();

		return $this->render('movie/movie.html.twig', [
			'movie' => $movie,
			'user' => $user,
		]);
	}
}
