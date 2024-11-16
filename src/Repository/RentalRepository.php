<?php

namespace App\Repository;

use App\Entity\Rental;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rental>
 */
class RentalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rental::class);
    }


    //admin dashboard
    public function calculateTotalRevenue(): float
    {
        return (float) $this->createQueryBuilder('r')
            ->select('SUM(r.totalPrice)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    //admin dashboard
    public function getMonthlyRentalsForCurrentYear(): array
    {
        $currentYear = (new \DateTime())->format('Y');
        $monthlyRentals = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = new \DateTime("{$currentYear}-{$month}-01");
            $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

            $count = $this->createQueryBuilder('r')
                ->select('COUNT(r.id)')
                ->where('r.startDate BETWEEN :start AND :end')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate)
                ->getQuery()
                ->getSingleScalarResult();

            $monthlyRentals[] = $count;
        }

        return $monthlyRentals;
    }

    //admin dashboard
    public function getMonthlyRevenuesForCurrentYear(): array
    {
        $currentYear = (new \DateTime())->format('Y');
        $monthlyRevenues = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = new \DateTime("{$currentYear}-{$month}-01");
            $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

            $revenue = $this->createQueryBuilder('r')
                ->select('SUM(r.totalPrice)')
                ->where('r.startDate BETWEEN :start AND :end')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate)
                ->getQuery()
                ->getSingleScalarResult();

            $monthlyRevenues[] = $revenue ?? 0;
        }

        return $monthlyRevenues;
    }



    // /////////////////////////////////////////////////////////////

    //user dashboard owner
    public function calculateOwnerTotalRevenue(): float
    {
        $user = $this->getUser();

        return (float) $this->createQueryBuilder('r')
            ->join('r.vehicle', 'v')
            ->select('SUM(r.totalPrice)')
            ->where('v.owner = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }


    //user dashboard owner
    public function getOwnerMonthlyRentalsForCurrentYear(): array
    {
        $user = $this->getUser();
        $currentYear = (new \DateTime())->format('Y');
        $monthlyRentals = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = new \DateTime("{$currentYear}-{$month}-01");
            $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

            $count = $this->createQueryBuilder('r')
                ->join('r.vehicle', 'v')
                ->select('COUNT(r.id)')
                ->where('r.startDate BETWEEN :start AND :end')
                ->andWhere('v.owner = :user')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate)
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult();

            $monthlyRentals[] = $count;
        }

        return $monthlyRentals;
    }

    //user dashboard owner
    public function getOwnerMonthlyRevenuesForCurrentYear(): array
    {
        $user = $this->getUser();
        $currentYear = (new \DateTime())->format('Y');
        $monthlyRevenues = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = new \DateTime("{$currentYear}-{$month}-01");
            $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

            $revenue = $this->createQueryBuilder('r')
                ->join('r.vehicle', 'v')
                ->select('SUM(r.totalPrice)')
                ->where('r.startDate BETWEEN :start AND :end')
                ->andWhere('v.owner = :user')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate)
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult();

            $monthlyRevenues[] = $revenue ?? 0;
        }

        return $monthlyRevenues;
    }

}
