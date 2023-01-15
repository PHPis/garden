<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Binary\BinaryTree;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiIndexController extends AbstractController
{
    #[Route('api')]
    public function index(UserService $userService, BinaryTree $binaryTree): JsonResponse
    {
        $users = $userService->getAll();

        /** @var User $user */
        foreach ($users as $user) {
            $binaryTree->insert($user->getId(), $user);
        }

        return $this->json($binaryTree->show($binaryTree->root));
    }
}