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

    public function findFilteredEvents(array $criteria): array
    {
        $qb = $this->createQueryBuilder('e');


        if (isset($criteria['year_range'])) {
            $qb->andWhere('e.year BETWEEN :year1 AND :year2')
               ->setParameter('year1', $criteria['year_range'][0])
               ->setParameter('year2', $criteria['year_range'][1]);
        }


        if (isset($criteria['type'])) {
            $qb->join('e.event_type', 'et')
               ->andWhere('et.name = :eventTypeName')
               ->setParameter('eventTypeName', $criteria['type']);
        }


        if (isset($criteria['period'])) {
            $qb->join('e.event_period', 'ep')
               ->andWhere('ep.name = :eventPeriodName')
               ->setParameter('eventPeriodName', $criteria['period']);
        }


        if (isset($criteria['theme'])) {
            $qb->join('e.theme', 'etm')
               ->andWhere('etm.name = :eventThemeName')
               ->setParameter('eventThemeName', $criteria['theme']);
        }

        if (isset($criteria['zone'])) {
            $qb->join('e.zone', 'ez')
               ->andWhere('ez.name = :eventZoneName')
               ->setParameter('eventZoneName', $criteria['zone']);
        }

        $qb->orderBy('e.year', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findByYear(int $year, int $year2): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.year BETWEEN :year AND :year2')
            ->setParameter('year', $year)
            ->setParameter('year2', $year2)
            ->orderBy('e.year', 'ASC')
            ->getQuery()
            ->getResult();
    }

        public function findByType(string $eventTypeName): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.event_type', 'et')
            ->andWhere('et.name = :eventTypeName')
            ->setParameter('eventTypeName', $eventTypeName)
            ->orderBy('e.year', 'ASC')
            ->getQuery()
            ->getResult();
    }

        public function findByPeriod(string $eventPeriodName): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.event_period', 'ep')
            ->andWhere('ep.name = :eventPeriodName')
            ->setParameter('eventPeriodName', $eventPeriodName)
            ->orderBy('e.year', 'ASC')
            ->getQuery()
            ->getResult();
    }

        public function findByTheme(string $eventThemeName): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.theme', 'et')
            ->andWhere('et.name = :eventThemeName')
            ->setParameter('eventThemeName', $eventThemeName)
            ->orderBy('e.year', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
