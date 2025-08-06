<?php

namespace App\Repository;

use App\Entity\TemporalBoundary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TemporalBoundary>
 */
class TemporalBoundaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemporalBoundary::class);
    }
        /**
     * Trouve les entités dont start_date correspond exactement à l'année donnée
     *
     * @param int $startYear
     * @return TemporalBoundary[]
     */
    public function findByYear(int $startYear): array
    {
        return $this->createQueryBuilder('t')
            ->where(':start BETWEEN t.start_date AND t.end_date')
            ->setParameter('start', $startYear)
            ->getQuery()
            ->getResult();
    }
}
