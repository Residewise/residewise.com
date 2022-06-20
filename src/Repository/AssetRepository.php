<?php

namespace App\Repository;

use App\Entity\Asset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Money\Money;
use function strtolower;

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

    public function findBySearch(
        ?int $minSqm,
        ?int $maxSqm,
        ?Money $minPrice,
        ?Money $maxPrice,
        ?string $type,
        ?string $term,
        ?string $userType,
        ?string $title,
        ?int $floor,
        ?Money $agencyFee,
        ?string $address
    ): mixed {
        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin('a.publications', 'p');

        $qb->andWhere('p.isApproved = :true')->setParameter('true', true)->andWhere(
            "CURRENT_TIMESTAMP() BETWEEN p.startsAt AND p.endsAt"
        )->orderBy('a.createdAt', 'ASC');

        if ($floor) {
            $qb->andWhere(
                $qb->expr()->lte('a.floor', ':floor')
            )->setParameter('floor', $floor);
        }

        if ($address) {
            $qb->andWhere(
                $qb->expr()->like('LOWER(a.address)', ':address')
            )->setParameter('address', '%' . strtolower($address) . '%');
        }

        if ($title) {
            $qb->andWhere(
                $qb->expr()->like('LOWER(a.title)', ':title')
            )->setParameter('title', '%' . strtolower($title) . '%');
        }

        if ($agencyFee) {
            $qb->andWhere(
                $qb->expr()->lte('a.agencyFee', ':agencyFee')
            )->setParameter('agencyFee', $agencyFee);
        }

        if ($minSqm && ! $maxSqm) {
            $qb->andWhere(
                $qb->expr()->gte('a.sqm', ':minSqm')
            )->setParameter('minSqm', $minSqm);
        }

        if ($maxSqm && ! $minSqm) {
            $qb->andWhere(
                $qb->expr()->lte('a.sqm', ':maxSqm')
            )->setParameter('maxSqm', $maxSqm);
        }

        if ($maxSqm && $minSqm) {
            $qb->andWhere(
                $qb->expr()->between('a.sqm', ':minSqm', ':maxSqm')
            )->setParameter('minSqm', $minSqm)->setParameter('maxSqm', $maxSqm);
        }

        if ($minPrice && ! $maxPrice) {
            $qb->andWhere(
                $qb->expr()->gte('a.price', ':minPrice')
            )->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice && ! $minPrice) {
            $qb->andWhere(
                $qb->expr()->lte('a.price', ':maxPrice')
            )->setParameter('maxPrice', $maxPrice);
        }

        if ($minPrice && $maxPrice) {
            $qb->andWhere(
                $qb->expr()->between('a.price', ':minPrice', ':maxPrice')
            )->setParameter('minPrice', $minPrice)->setParameter('maxPrice', $maxPrice);
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

        if ($userType) {
            $qb->leftJoin('a.owner', 'o');
            $qb->leftJoin('o.agency', 'ag');

            match ($userType) {
                'owner' => $qb->andHaving('COUNT(ag.id) = 0')->groupBy('a.id'),
                'agent' => $qb->andHaving('COUNT(ag.id) = 1')->groupBy('a.id'),
                'default' => null
            };
        }


        return $qb->getQuery()->getResult();
    }

    public function findByTitle(string $title)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->andWhere('a.title LIKE :title')
            ->setParameter('title', '%'.$title.'%');

        return $qb->getQuery()->getResult();
    }
}
