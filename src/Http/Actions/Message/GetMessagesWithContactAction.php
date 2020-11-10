<?php


namespace App\Http\Actions\Message;


use App\Exceptions\HttpExceptionCustomNotFound;
use Psr\Http\Message\ResponseInterface as Response;

class GetMessagesWithContactAction extends MessageAction
{
    /**
     * {@inheritdoc}
     * @throws HttpExceptionCustomNotFound
     */
    protected function action(): Response
    {
        $user = $this->getUser();
        $contactId = $this->resolveArg("contact_id");
        $contact = $this->userService->findByIdOr404($contactId);
        $messages = $this->messageService->getMessagesWithContact($user, $contact);
        $this->logger->info("User " . $user->getId() . " viewed messages.");
        return $this->respondWithPayload($messages);
    }
}