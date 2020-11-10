<?php

namespace App\Repositories;

use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
    /**
     * @var EntityRepository
     */
    private $doctrineRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->doctrineRepository = $entityManager->getRepository(User::class);
    }

    /**
     * @return User[]
     */
    public function all(): array
    {
        return $this->doctrineRepository->findAll();
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function findById(string $id)
    {
        return $this->doctrineRepository->find($id);
    }
}