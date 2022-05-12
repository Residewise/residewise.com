<?php

namespace App\Repository;

use App\Entity\Asset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Asset|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asset|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asset[]    findAll()
 * @method Asset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asset::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Asset $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Asset $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Asset[] Returns an array of Asset objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Asset
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findBySearch(?int $minSqm, ?int $maxSqm, ?int $minPrice, ?int $maxPrice, ?string $type, ?string $term)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->andWhere(
            $qb->expr()->eq('a.isPublished', ':true')
        )->setParameter('true', true)
            ->orderBy('a.createdAt', 'ASC');


        if ($minSqm && !$maxSqm) {
            $qb->andWhere(
                $qb->expr()->gte('a.sqm', ':minSqm')
            )->setParameter('minSqm', $minSqm);
        }

        if ($maxSqm && !$minSqm) {
            $qb->andWhere(
                $qb->expr()->lte('a.sqm', ':maxSqm')
            )->setParameter('maxSqm', $maxSqm);
        }

        if ($maxSqm && $minSqm) {
            $qb->andWhere(
                $qb->expr()->between('a.sqm', ':minSqm', ':maxSqm')
            )->setParameter('minSqm', $minSqm)->setParameter('maxSqm', $maxSqm);
        }


        if ($minPrice && !$maxPrice) {
            $qb->andWhere(
                $qb->expr()->gte('a.fee', ':minPrice')
            )->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice && !$minPrice) {
            $qb->andWhere(
                $qb->expr()->lte('a.fee', ':maxPrice')
            )->setParameter('maxPrice', $maxPrice);
        }

        if ($minPrice && $maxPrice) {
            $qb->andWhere(
                $qb->expr()->between('a.fee', ':minPrice', ':maxPrice')
            )->setParameter('minPrice', $minPrice)
                ->setParameter('maxPrice', $maxPrice);
        }

        if ($type) {
            $qb->andWhere(
                $qb->expr()->eq('a.type', ':type')
            )->setParameter('type', $type);
        }

        if ($term) {
            $qb->andWhere(
                $qb->expr()->eq('a.term', ':term')
            )->setParameter('term', $term);
        }

        return $qb->getQuery()->getResult();

    }
}
