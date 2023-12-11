<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Binary\BinaryTree;
use App\Services\UserBinaryTree;
use App\Services\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * Использование базовой реализации бинарного дерева
     * @param UserService $userService
     * @param BinaryTree $binaryTree
     * @return Response
     */
    #[Route('')]
    public function indexAction(UserService $userService, BinaryTree $binaryTree): Response
    {
        $users = $userService->getAll();

        /** @var User $user */
        foreach ($users as $user) {
            $binaryTree->insert($user->getId(), $user);
        }

        // For testing delete function
        $binaryTree = $binaryTree->delete(12);
        $binaryTree = $binaryTree->delete(13);
        //

        return $this->render('index.html.twig', [
            'tree' => $binaryTree->showByStage($binaryTree->root)
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('user')]
    public function userAction(UserService $userService, UserBinaryTree $binaryTree): Response
    {
        $users = $userService->getAll();

        /** @var User $user */
        foreach ($users as $user) {
            $binaryTree->insert($user->getPassportNumber(), $user);
        }

        // For testing delete function
        $binaryTree = $binaryTree->delete(741803);
        $binaryTree = $binaryTree->delete(627777);
        //

        return $this->render('index.html.twig', [
            'tree' => $binaryTree->showByStage($binaryTree->root)
        ]);
    }

    #[Route('maxbranch')]
    public function getMaxBranch(Request $request, UserService $userService, BinaryTree $binaryTree): Response
    {
        $search = $request->get('search');
        $users = $userService->getAll();

        /** @var User $user */
        foreach ($users as $user) {
            $binaryTree->insert($user->getId(), $user);
        }
        // For testing delete function
        $binaryTree = $binaryTree->delete(12);
        $binaryTree = $binaryTree->delete(13);
        //

        $maxBranch = $binaryTree->searchMaxBranch($search, $binaryTree->root);

        return $this->render('index.html.twig', [
            'tree' => $binaryTree->showByStage($binaryTree->root),
            'message' => $maxBranch,
            'highlight' => $search
        ]);
    }
}