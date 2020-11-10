<?php


namespace App\Services;

use App\Entities\User;
use App\Exceptions\HttpExceptionCustomNotFound;
use App\Repositories\UserRepository;
use App\Serializers\UserSerializer;

class UserService
{

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * @return User[]
     */
    public function all(): array
    {
        $users = $this->repository->all();
        return UserSerializer::serializeArray($users);
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function findById(string $id)
    {
        return $this->repository->findById($id);
    }

    /**
     * @param string $id
     * @return User
     * @throws HttpExceptionCustomNotFound
     */
    public function findByIdOr404(string $id): User
    {
        $user = $this->findById($id);
        if ($user == null) {
            throw new HttpExceptionCustomNotFound("User Not Found");
        }
        return $user;
    }
}