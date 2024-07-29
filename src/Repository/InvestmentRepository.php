<?php

namespace App\Repository;

use App\Entity\Investment;
use App\Entity\Loan;
use App\Entity\Tranche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Investment>
 *
 * @method Investment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Investment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Investment[]    findAll()
 * @method Investment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investment::class);
    }

//    /**
//     * @return Investment[] Returns an array of Investment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Investment
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function invest(Tranche $tranche, Investment $investment): bool {
        if ($investment->getAmount() + $tranche->getInvestedAmount() > $tranche->getMaxAmount()) {
            return false;
        }
        $tranche->setInvestedAmount($tranche->getInvestedAmount() + $investment->getAmount());
        return true;
    }

    public function calculateInterest(Investment $investment, \DateTimeInterface $startDate, \DateTimeInterface $endDate): float {
        $daysInMonth = (int)$endDate->format('t');
        $dailyInterestRate = $investment->getTranche()->getMonthlyInterestRate() / $daysInMonth;
        $investedDays = $endDate->diff($investment->getDate())->days + 1;
        $investedPeriodInterestRate = $dailyInterestRate * $investedDays;
        return ($investment->getAmount() / 100) * $investedPeriodInterestRate;
    }
}
