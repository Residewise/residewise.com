<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<Conversation>
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, Conversation::class);
    }

    public function add(Conversation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Conversation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByUserAndKeyword(User $user, ?string $keyword, int $page = 1): mixed
    {
        $qb = $this->createQueryBuilder('c');
        $qb->orderBy('c.createdAt', 'ASC');

        $qb->join('c.people', 'p');

        $qb->andWhere('p.id = :id')
            ->setParameter('id', $user->getId(), 'uuid');

        if ($keyword) {
            $qb->join('c.messages', 'm');
            $content = $qb->expr()
                ->like('LOWER(m.content)', ':keyword');
            $title = $qb->expr()
                ->like('LOWER(c.title)', ':keyword');

            $qb->andWhere(
                $qb->expr()
                    ->orX($title, $content)
            )->setParameter('keyword', '%' . strtolower($keyword) . '%');
        }

        return $this->paginator->paginate($qb->getQuery(), $page, 15);
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
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
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param ArrayCollection<int, User> $people
     */
    public function findByUsers(ArrayCollection $people): mixed
    {
        $qb = $this->createQueryBuilder('c');
        $qb->andWhere('c.people IN (:people)')
            ->setParameter('people', $people);

        return $qb->getQuery()
            ->getOneOrNullResult();

    }
}
