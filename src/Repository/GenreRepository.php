<?php

namespace App\Repository;

use App\DTO\FilterGenre;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Genre>
 *
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    /**
     * constructor.
     */
    public function __construct(ManagerRegistry $registry, private LoggerInterface $logger)
    {
        parent::__construct($registry, Genre::class);
    }

    /**
     * saves.
     */
    public function save(Genre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filterAll(FilterGenre $dtoFilter)
    {
        $this->logger->info('Filtermethode wurde für Genre aufgerufen.');
        $qb = $this->createQueryBuilder('b');

        if ($dtoFilter->genre) {
            $this->logger->debug('Filter Name: {name}', ['name' => $dtoFilter->name]);
            $qb = $qb->andWhere('b.genre like :genre')
                ->setParameter('genre', $dtoFilter->genre.'%');
        }
    }

    public function remove(Genre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
