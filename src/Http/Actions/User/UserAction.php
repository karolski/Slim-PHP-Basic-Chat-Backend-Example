<?php
declare(strict_types=1);

namespace App\Http\Actions\User;

use App\Http\Actions\AuthenticatedAction;
use App\Services\UserService;
use Psr\Log\LoggerInterface;

abstract class UserAction extends AuthenticatedAction
{

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @param LoggerInterface $logger
     * @param UserService $userService
     */
    public function __construct(LoggerInterface $logger, UserService $userService)
    {
        parent::__construct($logger);
        $this->userService = $userService;
    }
}
