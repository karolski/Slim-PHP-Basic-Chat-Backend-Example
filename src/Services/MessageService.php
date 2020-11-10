<?php


namespace App\Services;

use App\Entities\User;
use App\Exceptions\HttpExceptionCustomNotFound;
use App\Repositories\UserRepository;
use App\Serializers\MessageSerializer;
use App\Repositories\MessageRepository;

class MessageService
{

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(MessageRepository $messageRepository, UserRepository $userRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
    }


    /**
     * @param User $user
     * @param User $contact
     * @return array
     */
    public function getMessagesWithContact(User $user, User $contact): array
    {
        $messages = $this->messageRepository->getMessagesWithContact($user, $contact);
        return MessageSerializer::serializeArray($messages);
    }


    /**
     * @param User $author
     * @param array $data
     * @return array
     * @throws HttpExceptionCustomNotFound
     */
    public function createMessage(User $author, array $data): array
    {
        $toUser = $this->userRepository->findById($data["to"]);

        if ($toUser == null) {
            throw new HttpExceptionCustomNotFound("Message recipient not not found");
        }

        $new_message = $this->messageRepository->createMessage($author, $toUser, $data["content"]);

        return MessageSerializer::serialize($new_message);
    }


}