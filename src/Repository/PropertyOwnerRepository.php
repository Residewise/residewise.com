<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PropertyOwner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PropertyOwner|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyOwner|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyOwner[]    findAll()
 * @method PropertyOwner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyOwnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyOwner::class);
    }

    // /**
    //  * @return PropertyOwner[] Returns an array of PropertyOwner objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PropertyOwner
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
