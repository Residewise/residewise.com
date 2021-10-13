<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ContractParty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContractParty|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContractParty|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContractParty[]    findAll()
 * @method ContractParty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractPartyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractParty::class);
    }

    // /**
    //  * @return ContractParty[] Returns an array of ContractParty objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContractParty
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
