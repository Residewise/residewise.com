<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function dump;

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
    ) {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Conversation $entity, bool $flush = true): void
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
    public function remove(Conversation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param null|User|UserInterface $user
     */
    public function findByUserAndKeyword(null|User|UserInterface $user, ?string $keyword, int $page = 1): mixed
    {
        $qb = $this->createQueryBuilder('c');
        $qb->orderBy('c.createdAt', 'ASC');

        if ($user) {
            $qb->join('c.users', 'u');

            $qb->andWhere('u.id = :id')->setParameter(
                'id',
                $user->getId()->toRfc4122()
            );
        }

        if ($keyword) {
            $qb->join('c.messages', 'm');
            $content = $qb->expr()->like('LOWER(m.content)', ':keyword');
            $title = $qb->expr()->like('LOWER(c.title)', ':keyword');

            $qb->andWhere(
                $qb->expr()->orX($title, $content)
            )->setParameter('keyword', '%' . strtolower($keyword) . '%');
        }

        return $this->paginator->paginate(
            $qb->getQuery(),
            $page,
            15
        );
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

    public function findByUsers(ArrayCollection $users)
    {
        dump($users);
        $qb = $this->createQueryBuilder('c');
        $qb->andWhere(
            'c.users IN (:users)'
        )->setParameter('users', $users);

       return $qb->getQuery()->getOneOrNullResult();

    }

}
