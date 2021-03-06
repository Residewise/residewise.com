<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function add(Review $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Review $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getAverageUserRating(User $user): mixed
    {
        $qb = $this->createQueryBuilder('r');
        $qb->leftJoin('r.user', 'u');
        $qb->select('AVG(r.rating) as AVG_RATING, COUNT(r.id) as TOTAL_COUNT')
            ->where('u.id = :id')
            ->setParameter('id', $user->getId(), 'uuid');

        return $qb->getQuery()
            ->getScalarResult();
    }
}
