<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	public function __construct(EntityManagerInterface $manager)
	{
		$this->em = $manager;
	}

	/**
	 * @Route("/movies", name="movies")
	 *
	 * @param MovieRepository $movieRepository
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @return Response
	 */
    public function index(MovieRepository $movieRepository, PaginatorInterface $paginator, Request $request)
    {
    	$movies = $paginator->paginate(
    		$movieRepository->findAll(), // Query
			$request->query->get('page', 1), // Page number
			6 // Limit per page
		);

        return $this->render('movie/index.html.twig', [
            'movies' => $movies
        ]);
    }

	/**
	 * @Route("/movie/new", name="movie_new")
	 *
	 * @param Request $request
	 * @return RedirectResponse|Response
	 */
	public function new(Request $request)
	{
		$movie = new Movie();
		$form = $this->createForm(MovieType::class, $movie);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->persist($movie);
			$this->em->flush();

			$this->addFlash('success', 'Votre film à bien été ajouter !');

			return $this->redirectToRoute('movie_show', ['slug' => $movie->getSlug()]);
		}

		return $this->render('movie/add.html.twig', [
			'form' => $form->createView()
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
		return $this->render('movie/movie.html.twig', [
			'movie' => $movie,
		]);
	}

	/**
	 * @Route("/movie/{slug}/edit", name="movie_edit")
	 *
	 * @param Request $request
	 * @param Movie $movie
	 * @return RedirectResponse|Response
	 */
	public function edit(Request $request, Movie $movie)
	{
		$form = $this->createForm(MovieType::class, $movie);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->flush();

			$this->addFlash('success', "Le film {$movie->getTitle()} à bien été modifier !");

			return $this->redirectToRoute('movie_show', ['slug' => $movie->getSlug()]);
		}

		return $this->render('movie/edit.html.twig', [
			'movie' => $movie,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/movie/{slug}/delete", name="movie_delete")
	 *
	 * @param Movie $movie
	 * @return RedirectResponse
	 */
	public function delete(Movie $movie): RedirectResponse
	{
		$this->em->remove($movie);
		$this->em->flush();

		$this->addFlash('success', 'Le film a bien été supprimer !');

		return $this->redirectToRoute('movies');
	}

}
