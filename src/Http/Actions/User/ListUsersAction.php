<?php
declare(strict_types=1);

namespace App\Http\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $user = $this->getUser();
        $users = $this->userService->all();

        $this->logger->info("User " . $user->getId() . "viewed the user list.");

        return $this->respondWithPayload($users);
    }
}
