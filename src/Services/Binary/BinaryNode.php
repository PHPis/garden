<?php

namespace App\Services\Binary;

/**
 * Class BinaryNode узел бинарного дерева
 * @package App\Services\Binary
 */
class BinaryNode
{
    /**
     * Значение в узле
     * @var mixed
     */
    public mixed $value;

    /**
     * Левый потомок
     * @var BinaryNode|null
     */
    public ?BinaryNode $left = null;

    /**
     * Правый потомок
     * @var BinaryNode|null
     */
    public ?BinaryNode $right = null;

    /**
     * Родитель
     * @var BinaryNode|null
     */
    public ?BinaryNode $parent = null;

    /**
     * Высота дерева/поддерева с потомками
     * @var int
     */
    public int $height = 1;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Пересчёт высоты узла
     * @return int
     */
    public function setHeight(): int
    {
        if (!$this->left && !$this->right) {
            return $this->height = 1;
        } elseif (!$this->left || !$this->right) {
            return $this->height = $this->left ? ($this->left->height + 1) : ($this->right->height + 1);
        }
        return $this->height = (
            $this->left->height > $this->right->height
                ? $this->left->height
                : $this->right->height
            ) + 1;
    }
}