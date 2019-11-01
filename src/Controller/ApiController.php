<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
	private $movieRepository;

	public function __construct(MovieRepository $movieRepository)
	{
		$this->movieRepository = $movieRepository;
	}


	/**
	 * @Route("/api", name="api_endpoint")
	 *
	 * @return JsonResponse
	 */
	public function endPoint()
	{
		return new JsonResponse(
			[
				'list_movies' => $this->generateUrl('api_list_movies'),
				'show_movie' => $this->generateUrl('api_show_movie', [
					'id' => 'id'
				])
			]
		);
	}

	/**
	 * @Route("/api/list", name="api_list_movies")
	 *
	 * @return JsonResponse
	 */
	public function listMovies()
	{
		$movies = $this->movieRepository->findAll();
		$moviesTab = [];

		foreach ($movies as $movie) {
			$moviesTab[] = $this->getMovieInfos($movie);
		}

		return new JsonResponse($moviesTab, Response::HTTP_OK);
	}

	/**
	 * @Route("/api/movie/{id}", name="api_show_movie")
	 *
	 * @param int id
	 * @return JsonResponse
	 */
	public function showMovieById(int $id)
	{
		$movie = $this->getMovieInfos($this->movieRepository->find($id));

		return new JsonResponse($movie, Response::HTTP_OK);
	}

	/**
	 * @param Movie $movie
	 * @return array
	 */
	private function getMovieInfos(Movie $movie): array
	{
		$categoriesTab = [];
		foreach ($movie->getCategories() as $category) {
			$categoriesTab[] = $category->getTitle();
		}

		$actorsTab = [];
		foreach ($movie->getActors() as $actor) {
			$actorsTab[] = $actor->getFullName();
		}

		return [
			'id' => $movie->getId(),
			'title' => $movie->getTitle(),
			'released_at' => $movie->getReleasedAt()->format('m/d/Y'),
			'synopsis' => $movie->getSynopsis(),
			'categories' => $categoriesTab,
			'actors' => $actorsTab
		];
	}
}