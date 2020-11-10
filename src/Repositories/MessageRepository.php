<?php

namespace App\Repositories;

use App\Entities\Message;
use App\Entities\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;


class MessageRepository
{
    /**
     * @var EntityRepository
     */
    private $doctrineRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MessageRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->doctrineRepository = $entityManager->getRepository(Message::class);
    }

    /**
     * @param User $user
     * @param User $contact
     * @return int|mixed|string
     */
    public function getMessagesWithContact(User $user, User $contact)
    {
        return $this->getMessagesBetweenUsersQuery(
            $user->getId(),
            $contact->getId())
            ->getResult();
    }

    /**
     * @param User $author
     * @param User $toUser
     * @param string $content
     * @return Message
     */
    public function createMessage(User $author, User $toUser, string $content): Message
    {
        $message = new Message();
        $message->setFrom($author);
        $message->setTo($toUser);
        $message->setContent($content);
        $message->setCreatedAt(new DateTime());
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }

    /**
     * @param string $user1Id
     * @param string $user2Id
     * @return \Doctrine\ORM\Query
     */
    private function getMessagesBetweenUsersQuery($user1Id, $user2Id): Query
    {
        return $this->doctrineRepository
            ->createQueryBuilder('message')
            ->where('message.from = :user2_id AND message.to = :user1_id')
            ->orWhere('message.from = :user1_id AND message.to = :user2_id')
            ->setParameters([
                "user1_id" => $user1Id,
                "user2_id" => $user2Id,
            ])
            ->orderBy('message.createdAt', 'DESC')
            ->getQuery();

    }
}