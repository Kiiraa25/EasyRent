<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

   
    //admin dashboard
    public function getMonthlySignupsForCurrentYear(): array
    {
        $currentYear = (new \DateTime())->format('Y');
        $monthlySignups = [];

        // Boucle sur les 12 mois de l'année
        for ($month = 1; $month <= 12; $month++) {
            $startDate = new \DateTime("{$currentYear}-{$month}-01");
            $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

            $count = $this->createQueryBuilder('u')
                ->join('u.profile', 'p')
                ->select('COUNT(u.id)')
                ->where('p.createdAt BETWEEN :start AND :end')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate)
                ->getQuery()
                ->getSingleScalarResult();

            $monthlySignups[] = $count;
        }

        return $monthlySignups;
    }




    //user dashboard Owner
    public function getOwnerMonthlySignupsForCurrentYear(): array
    {
        $currentYear = (new \DateTime())->format('Y');
        $monthlySignups = [];

        // Boucle sur les 12 mois de l'année
        for ($month = 1; $month <= 12; $month++) {
            $startDate = new \DateTime("{$currentYear}-{$month}-01");
            $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

            $count = $this->createQueryBuilder('u')
                ->join('u.profile', 'p')
                ->select('COUNT(u.id)')
                ->where('p.createdAt BETWEEN :start AND :end')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate)
                ->getQuery()
                ->getSingleScalarResult();

            $monthlySignups[] = $count;
        }

        return $monthlySignups;
    }









}
