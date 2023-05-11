<?php

namespace App\Repository;

use App\DTO\FilterMovie;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    /**
     * constructor
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry, private LoggerInterface $logger)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * saves
     * @param Movie $entity
     * @param bool $flush
     * @return void
     */
    public function save(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * removes
     * @param Movie $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filterAll(FilterMovie $dtoFilter)
    {
        $this->logger->info("Filtermethode wurde für Filme aufgerufen.");
        $qa = $this->createQueryBuilder('a');

        if($dtoFilter->name) {
            $this->logger->debug("Filter Name: {name}", ["name" => $dtoFilter->name]);
            $qa = $qa->andWhere("a.name like :name")
                ->setParameter("name", $dtoFilter->name . "%");
        }

        if($dtoFilter->orderby){
            $qa->orderBy($dtoFilter->orderby, $dtoFilter->orderdirection ?? "ASC");
        }

        return $qa
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
