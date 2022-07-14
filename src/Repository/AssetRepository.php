<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Agent;
use App\Entity\Asset;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Asset|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asset|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asset[]    findAll()
 * @method Asset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<Asset>
 */
class AssetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asset::class);
    }

    public function add(Asset $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Asset $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByTitle(string $title)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->andWhere('a.title LIKE :title')
            ->setParameter('title', '%' . $title . '%');

        return $qb->getQuery()
            ->getResult();
    }

    public function findBySearch(
        ?float  $minSqm,
        ?float  $maxSqm,
        ?float  $minPrice,
        ?float  $maxPrice,
        array   $types,
        ?string $term,
        ?string $userType,
        ?string $title,
        ?int    $floor,
        ?int    $agencyFee,
        ?string $address
    ): mixed
    {
        $qb = $this->createQueryBuilder('a');
        $qb->orderBy('a.createdAt', 'ASC');


        $qb->leftJoin('a.tender', 't');
        $qb->andWhere(
            $qb->expr()->isNotNull('t')
        );
        $qb->andWhere(
            $qb->expr()->between('CURRENT_DATE()', 't.startAt', 't.endAt')
        );

        if ($floor) {
            $qb->andWhere($qb->expr()->lte('a.floor', ':floor'))
                ->setParameter('floor', $floor);
        }

        if ($address) {
            $qb->andWhere(
                $qb->expr()
                    ->like('LOWER(a.address)', ':address')
            )->setParameter('address', '%' . strtolower($address) . '%');
        }

        if ($title) {
            $qb->andWhere(
                $qb->expr()
                    ->like('LOWER(a.title)', ':title')
            )->setParameter('title', '%' . strtolower($title) . '%');
        }

        if ($agencyFee) {
            $qb->andWhere($qb->expr()->lte('a.agencyFee', ':agencyFee'))
                ->setParameter('agencyFee', $agencyFee);
        }

        if ($minSqm && !$maxSqm) {
            $qb->andWhere($qb->expr()->gte('a.sqm', ':minSqm'))
                ->setParameter('minSqm', $minSqm);
        }

        if ($maxSqm && !$minSqm) {
            $qb->andWhere($qb->expr()->lte('a.sqm', ':maxSqm'))
                ->setParameter('maxSqm', $maxSqm);
        }

        if ($maxSqm && $minSqm) {
            $qb->andWhere($qb->expr()->between('a.sqm', ':minSqm', ':maxSqm'))
                ->setParameter('minSqm', $minSqm)
                ->setParameter('maxSqm', $maxSqm);
        }

        if ($minPrice && !$maxPrice) {
            $qb->andWhere($qb->expr()->gte('a.price', ':minPrice'))
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice && !$minPrice) {
            $qb->andWhere($qb->expr()->lte('a.price', ':maxPrice'))
                ->setParameter('maxPrice', $maxPrice);
        }

        if ($minPrice && $maxPrice) {
            $qb->andWhere(
                $qb->expr()
                    ->between('a.price', ':minPrice', ':maxPrice')
            )->setParameter('minPrice', $minPrice)
                ->setParameter('maxPrice', $maxPrice);
        }


        if ($types) {
            $qb->andWhere(
                $qb->expr()->in('a.type', ':types')
            )->setParameter('types', $types);
        }

        if ($term) {
            $qb->andWhere($qb->expr()->eq('a.term', ':term'))
                ->setParameter('term', $term);
        }

        if ($userType) {
            $qb->leftJoin('a.owner', 'p');
            match ($userType) {
                User::class => $qb->andWhere($qb->expr()->isInstanceOf('p', User::class)),
                Agent::class => $qb->andWhere($qb->expr()->isInstanceOf('p', Agent::class)),
                default => null
            };
        }

        return $qb->getQuery()
            ->getResult();
    }

}
