<?php

namespace App\Repository;

use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Route>
 */
class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    /**
     * Get distance statistics grouped by analytic code and optionally by time period
     *
     * @param \DateTimeImmutable|null $fromDate
     * @param \DateTimeImmutable|null $toDate
     * @param string $groupBy 'none', 'day', 'month', or 'year'
     * @return array
     */
    public function getDistanceStatistics(
        ?\DateTimeImmutable $fromDate,
        ?\DateTimeImmutable $toDate,
        string $groupBy = 'none'
    ): array {
        // Build base query
        $qb = $this->createQueryBuilder('r');

        // Apply date filters if provided
        if ($fromDate) {
            $qb->andWhere('r.createdAt >= :fromDate')
               ->setParameter('fromDate', $fromDate);
        }

        if ($toDate) {
            $qb->andWhere('r.createdAt <= :toDate')
               ->setParameter('toDate', $toDate);
        }

        // Group and aggregate based on groupBy parameter
        switch ($groupBy) {
            case 'day':
                return $this->getStatsByDay($qb, $fromDate, $toDate);
            
            case 'month':
                return $this->getStatsByMonth($qb, $fromDate, $toDate);
            
            case 'year':
                return $this->getStatsByYear($qb, $fromDate, $toDate);
            
            case 'none':
            default:
                return $this->getStatsByAnalyticCode($qb, $fromDate, $toDate);
        }
    }

    /**
     * Get statistics grouped only by analytic code (no time grouping)
     */
    private function getStatsByAnalyticCode($qb, $fromDate, $toDate): array
    {
        $qb->select('r.analyticCode', 'SUM(r.distanceKm) as totalDistanceKm')
           ->groupBy('r.analyticCode')
           ->orderBy('r.analyticCode', 'ASC');

        $results = $qb->getQuery()->getResult();

        return array_map(function ($row) use ($fromDate, $toDate) {
            $item = [
                'analyticCode' => $row['analyticCode'],
                'totalDistanceKm' => (float) $row['totalDistanceKm']
            ];

            // Add period dates if they were provided
            if ($fromDate) {
                $item['periodStart'] = $fromDate->format('Y-m-d');
            }
            if ($toDate) {
                $item['periodEnd'] = $toDate->format('Y-m-d');
            }

            return $item;
        }, $results);
    }

    /**
     * Get statistics grouped by analytic code and day
     */
    private function getStatsByDay($qb, $fromDate, $toDate): array
    {
        // Use DAY() function to group by day
        $qb->select(
                'r.analyticCode',
                'r.createdAt as groupDay',
                'SUM(r.distanceKm) as totalDistanceKm'
            )
           ->groupBy('r.analyticCode', 'groupDay')
           ->orderBy('r.analyticCode', 'ASC')
           ->addOrderBy('groupDay', 'ASC');

        $results = $qb->getQuery()->getResult();

        return array_map(function ($row) {
            return [
                'analyticCode' => $row['analyticCode'],
                'totalDistanceKm' => (float) $row['totalDistanceKm'],
                'group' => $row['groupDay']->format('Y-m-d'),
                'periodStart' => $row['groupDay']->format('Y-m-d'),
                'periodEnd' => $row['groupDay']->format('Y-m-d')
            ];
        }, $results);
    }

    /**
     * Get statistics grouped by analytic code and month
     */
    private function getStatsByMonth($qb, $fromDate, $toDate): array
    {
        // Use YEAR() and MONTH() functions to group by month
        $qb->select(
                'r.analyticCode',
                'YEAR(r.createdAt) AS groupYear',
                'MONTH(r.createdAt) AS groupMonth',
                'SUM(r.distanceKm) as totalDistanceKm'
            )
           ->groupBy('r.analyticCode', 'groupYear', 'groupMonth')
           ->orderBy('r.analyticCode', 'ASC')
           ->addOrderBy('groupYear', 'ASC')
           ->addOrderBy('groupMonth', 'ASC');

        $results = $qb->getQuery()->getResult();

        return array_map(function ($row) {
            $year = $row['groupYear'];
            $month = str_pad($row['groupMonth'], 2, '0', STR_PAD_LEFT);
            $groupKey = "$year-$month";

            // Calculate period start and end
            $periodStart = "$year-$month-01";
            $periodEnd = date('Y-m-t', strtotime($periodStart)); // Last day of month

            return [
                'analyticCode' => $row['analyticCode'],
                'totalDistanceKm' => (float) $row['totalDistanceKm'],
                'group' => $groupKey,
                'periodStart' => $periodStart,
                'periodEnd' => $periodEnd
            ];
        }, $results);
    }

    /**
     * Get statistics grouped by analytic code and year
     */
    private function getStatsByYear($qb, $fromDate, $toDate): array
    {
        // Use YEAR() function to group by year
        $qb->select(
                'r.analyticCode',
                'YEAR(r.createdAt) as groupYear',
                'SUM(r.distanceKm) as totalDistanceKm'
            )
           ->groupBy('r.analyticCode', 'groupYear')
           ->orderBy('r.analyticCode', 'ASC')
           ->addOrderBy('groupYear', 'ASC');

        $results = $qb->getQuery()->getResult();

        return array_map(function ($row) {
            $year = $row['groupYear'];

            return [
                'analyticCode' => $row['analyticCode'],
                'totalDistanceKm' => (float) $row['totalDistanceKm'],
                'group' => (string) $year,
                'periodStart' => "$year-01-01",
                'periodEnd' => "$year-12-31"
            ];
        }, $results);
    }

    public function save(Route $route): void
    {
        $this->getEntityManager()->persist($route);
        $this->getEntityManager()->flush();
    }
}
