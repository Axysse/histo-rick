<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Events>
 */
class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

//    /**
//     * @return Events[] Returns an array of Events objects
//     */
    public function findByYear(int $year): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.year = :year')
            ->setParameter('year', $year)
            ->orderBy('e.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
