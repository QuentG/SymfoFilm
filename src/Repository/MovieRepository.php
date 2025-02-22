<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

	/**
	 * @param int $limit
	 * @return mixed
	 */
    public function findLatestMovies($limit = 5)
	{
		return $this->createQueryBuilder('m')
			->orderBy('m.releasedAt', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult()
		;
	}

}
