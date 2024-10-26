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

    
    public function getMonthlyRevenue(): float
    {
        $startOfMonth = new \DateTime('first day of this month 00:00:00');
        $endOfMonth = new \DateTime('last day of this month 23:59:59');
    
        return $this->createQueryBuilder('r')
            ->select('SUM(r.totalPrice)')
            ->where('r.startDate BETWEEN :start AND :end')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getRentalsForCurrentYear(): array
{
    $startOfYear = new \DateTime('first day of January this year');
    $endOfYear = new \DateTime('last day of December this year 23:59:59');

    return $this->createQueryBuilder('r')
        ->where('r.startDate BETWEEN :start AND :end')
        ->setParameter('start', $startOfYear)
        ->setParameter('end', $endOfYear)
        ->getQuery()
        ->getResult();
}

public function countRentalsForCurrentMonth(): int
{
    $startOfMonth = new \DateTime('first day of this month');
    $endOfMonth = new \DateTime('last day of this month 23:59:59');

    return $this->createQueryBuilder('r')
        ->select('COUNT(r.id)')
        ->where('r.startDate BETWEEN :start AND :end')
        ->setParameter('start', $startOfMonth)
        ->setParameter('end', $endOfMonth)
        ->getQuery()
        ->getSingleScalarResult();
}

public function calculateMonthlyRevenue(): float
{
    $startOfMonth = new \DateTime('first day of this month');
    $endOfMonth = new \DateTime('last day of this month 23:59:59');

    return (float) $this->createQueryBuilder('r')
        ->select('SUM(r.totalPrice)')
        ->where('r.startDate BETWEEN :start AND :end')
        ->setParameter('start', $startOfMonth)
        ->setParameter('end', $endOfMonth)
        ->getQuery()
        ->getSingleScalarResult();
}

public function calculateTotalRevenue(): float
{
    return (float) $this->createQueryBuilder('r')
        ->select('SUM(r.totalPrice)')
        ->getQuery()
        ->getSingleScalarResult();
}

// RentalRepository.php

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





    

    


    


//    /**
//     * @return Rental[] Returns an array of Rental objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rental
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
