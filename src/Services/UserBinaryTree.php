<?php

namespace App\Services;

use App\Entity\User;
use App\Services\Binary\BinaryNode;
use Exception;
use JetBrains\PhpStorm\Pure;

class UserBinaryTree extends Binary\BinaryTree
{
    /**
     * @throws Exception
     */
    public function insert(int $value, mixed $data = null)
    {
        if (!is_a($data, User::class)) {
            throw new Exception('Only for UserClass');
        }
        parent::insert($value, $data);
    }


    /**
     * Данные выводимые из узла
     * @param BinaryNode $root
     * @return string
     */
    #[Pure] protected function showNode(BinaryNode $root): string
    {
        /** @var User $user */
        $user = $root->data;
        return '(' . $user->getPassportNumber() . ') ' . $user->getFirstName() . ' ' . $user->getLastName();
    }

}