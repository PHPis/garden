<?php

namespace App\Controller;

use App\Services\Binary\BinaryTree;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiIndexController extends AbstractController
{
    #[Route('api/index')]
    public function index(UserService $userService, BinaryTree $binaryTree): JsonResponse
    {
        $users = $userService->getAll();

        foreach ($users as $user) {
            $binaryTree->insert($user);
        }

        return $this->json($binaryTree->show($binaryTree->root));
    }
}