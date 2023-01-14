<?php

namespace App\Controller;

use App\Services\Binary\BinaryTree;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('')]
    public function indexAction(UserService $userService, BinaryTree $binaryTree): Response
    {
        $users = $userService->getAll();

        foreach ($users as $user) {
            $binaryTree->insert($user);
        }

        return $this->render('index.html.twig', [
            'tree' => $binaryTree->showByStage($binaryTree->root)
        ]);
    }
}