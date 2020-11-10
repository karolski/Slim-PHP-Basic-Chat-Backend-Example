<?php
declare(strict_types=1);

namespace App\Http\Actions\Message;

use App\Http\Actions\AuthenticatedAction;
use App\Services\MessageService;
use App\Services\UserService;
use Psr\Log\LoggerInterface;

abstract class MessageAction extends AuthenticatedAction
{

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var MessageService
     */
    protected $messageService;

    /**
     * @param LoggerInterface $logger
     * @param UserService $userService
     * @param MessageService $messageService
     */
    public function __construct(LoggerInterface $logger, UserService $userService, MessageService $messageService)
    {
        parent::__construct($logger);
        $this->userService = $userService;
        $this->messageService = $messageService;
    }
}
