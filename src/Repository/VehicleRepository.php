<?php

namespace App\Repository;

use App\Entity\Vehicle;
use App\Enum\FuelTypeEnum;
use App\Enum\GearboxTypeEnum;
use App\Enum\RentalStatusEnum;
use App\Enum\VehicleCategoryEnum;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicle>
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function search(
        ?string $search,
        ?DateTime $startDate,
        ?DateTime $endDate,
        ?VehicleCategoryEnum $vehicleCategory,
        ?GearboxTypeEnum $gearboxType,
        ?FuelTypeEnum $fuelType,
        ?int $totalPrice
    ) {
        $qb = $this->createQueryBuilder('v')
            ->leftJoin('v.rentals', 'r')
            ->andWhere('r.id IS NULL OR (r.status IN (:nonValidStatuses) OR r.endDate < :startDate OR r.startDate > :endDate)')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('nonValidStatuses', [
                RentalStatusEnum::ANNULEE->value,
                RentalStatusEnum::EN_ATTENTE_VALIDATION->value,
                RentalStatusEnum::REFUSEE->value,
                RentalStatusEnum::EXPIREE->value,
                RentalStatusEnum::DEMANDE_ANNULEE->value,
            ]);
        if ($search) {
            $qb->andWhere('v.address LIKE :search OR v.postalCode LIKE :search OR v.city LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }


        if ($gearboxType) {
            $qb->andWhere('v.gearboxType LIKE :gearboxType')
                ->setParameter('gearboxType', $gearboxType);
        }

        if ($fuelType) {
            $qb->andWhere('v.fuelType LIKE :fuelType')
                ->setParameter('fuelType', $fuelType);
        }

        if ($vehicleCategory) {
            $qb->join('v.model', 'm')
                ->andWhere('m.vehicleCategory LIKE :vehicleCategory')
                ->setParameter('vehicleCategory', $vehicleCategory);
        }

        $days = $startDate->diff($endDate)->days;

        if ($totalPrice) {
            $qb->andWhere('v.pricePerDay * :days <= :totalPrice')
                ->setParameter('days', $days)
                ->setParameter('totalPrice', $totalPrice);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Vehicle[] Returns an array of Vehicle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicle
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
