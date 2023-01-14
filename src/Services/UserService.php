<?php


namespace App\Services;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function getAll(): array
    {
        /**@var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        return $userRepository->findAll();
    }
}