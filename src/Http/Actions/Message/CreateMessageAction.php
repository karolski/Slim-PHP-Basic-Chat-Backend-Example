<?php


namespace App\Http\Actions\Message;


use App\Constants\StatusCode;
use App\Exceptions\HttpExceptionCustomNotFound;
use App\Http\Validators\CreateMessageValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Exception\HttpBadRequestException;

class CreateMessageAction extends MessageAction
{
    /**
     * @return Response
     * @throws HttpBadRequestException
     * @throws HttpExceptionCustomNotFound
     */
    public function action(): Response
    {
        $data = $this->getJsonData();
        $this->validateJson($data);
        $user = $this->getUser();
        $newMessage = $this->messageService->createMessage($user, $data);

        $this->logger->info("User " . $user->getId() . " created a message messages.");

        return $this->respondWithPayload($newMessage, StatusCode::CREATED);
    }

    /**
     * @param array $data
     * @throws HttpBadRequestException
     * @return void
     */
    private function validateJson(array $data): void
    {
        try {
            CreateMessageValidator::validate($data);
        } catch (NestedValidationException $exception) {
            $message = implode(", ", $exception->getMessages());
            throw new HttpBadRequestException($this->request, $message);
        }
    }

}