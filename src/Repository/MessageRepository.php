<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function add(Message $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Message $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByConversation(Conversation $conversation): array
    {
        $qb = $this->createQueryBuilder('m');
        $qb->orderBy('m.createdAt', 'DESC');
        $qb->addGroupBy('m.id');
        $qb->addGroupBy('m.conversation');

        $qb->andWhere(
            $qb->expr()
                ->eq('m.conversation', ':conversation')
        )->setParameter('conversation', $conversation);

        $result = (array)$qb->getQuery()
            ->setMaxResults(10)
            ->setFirstResult(0)
            ->getResult();

        return array_reverse($result);
    }
}
