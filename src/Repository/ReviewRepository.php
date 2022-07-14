<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Person;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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

    public function getAverageUserRating(null|Person $person)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->leftJoin('r.person', 'p');
        $qb->select('AVG(r.rating) as AVG_RATING, COUNT(r.id) as TOTAL_COUNT')
            ->where('p.id = :id')
            ->setParameter('id', $person->getId(), 'uuid');

        return $qb->getQuery()
            ->getScalarResult();
    }
}
