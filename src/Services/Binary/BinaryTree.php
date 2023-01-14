<?php

namespace App\Services\Binary;

class BinaryTree
{
    /**
     * Корень дерева
     * @var BinaryNode|null
     */
    public ?BinaryNode $root = NULL;

    /**
     * Проверка на пустоту
     * @return bool
     */
    public function isEmpty(): bool
    {
        return is_null($this->root);
    }

    /**
     * Вставка в дерево
     * @param $value
     */
    public function insert($value)
    {
        $node = new BinaryNode($value);
        $this->insertNode($node, $this->root);
    }

    /**
     * Отображение дерева в виде массива по поддеревьям
     * @param BinaryNode|null $root
     * @return array|null
     */
    public function show(?BinaryNode $root): ?array
    {
        if (!$root) {
            return null;
        }
        $array = [$root->value->getId()];
        return array_merge($array, [$this->show($root->left)], [$this->show($root->right)]);
    }

    /**
     * Отображение дерева по уровням вложенности
     * @param BinaryNode|null $root
     * @param int $deep
     * @param array $array
     * @return array|null
     */
    public function showByStage(?BinaryNode $root, int $deep = 0, array &$array = []): ?array
    {
        if (!$root) {
            return [];
        }
        $array[$deep][] = $root->value->getId() . 'Key';
        $deep++;
        $this->showByStage($root->left, $deep, $array);
        $this->showByStage($root->right, $deep, $array);
        return $array;
    }

    /**
     * Процесс вставки в дерево
     * @param BinaryNode $node
     * @param BinaryNode|null $subtree
     * @return $this
     */
    protected function insertNode(BinaryNode $node, ?BinaryNode &$subtree): static
    {
        if (is_null($subtree)) {
            $subtree = $node;
            if (!$this->root) {
                $this->root = $node;
            }
        } else {
            if ($node->value < $subtree->value) {
                $this->insertNode($node, $subtree->left);
            } elseif ($node->value > $subtree->value) {
                $this->insertNode($node, $subtree->right);
            }
            if ($node->parent == null) {
                $node->parent = $subtree;
            }
        }
        $this->balance($subtree);
        return $this;
    }

    /**
     * Процесс балансировки дерева
     * @param BinaryNode $node
     * @return BinaryNode|null
     */
    protected function balance(BinaryNode $node): ?BinaryNode
    {
        $node->setHeight();

        if ($this->bfactor($node) == 2) {
            if ($node->right && $this->bfactor($node->right) < 0) {
                $node->right = $this->rotateRight($node->right);
            }

            return $this->rotateLeft($node);
        }
        if ($this->bfactor($node) == -2) {
            if ($node->left && $this->bfactor($node->left) > 0) {
                $node->left = $this->rotateLeft($node->left);
            }
            return $this->rotateRight($node);
        }
        return $node; // балансировка не нужна
    }

    /**
     * Поворот направо дерева/поддерева по узлу
     * @param BinaryNode $node
     * @return BinaryNode|null
     */
    protected function rotateRight(BinaryNode $node): ?BinaryNode
    {
        $parent = $node->left;
        if ($node == $this->root) {
            $this->root = $parent;
        }
        $node->left = $parent->right;
        $parent->right = $node;
        $node->setHeight();
        $parent->setHeight();
        return $parent;
    }

    /**
     * Поворот налево дерева/поддерева по узлу
     * @param BinaryNode $node
     * @return BinaryNode|null
     */
    protected function rotateLeft(BinaryNode $node): ?BinaryNode
    {
        $parent = $node->right;
        if ($node === $this->root) {
            $this->root = $parent;
        } else {
            $parent->parent = $node->parent;
            if ($node->parent->right === $node) {
                $node->parent->right = $parent;
            } else {
                $node->parent->left = $parent;
            }
        }
        $node->right = $parent->left;
        $node->parent = $parent;
        $parent->left = $node;
        $node->setHeight();
        $parent->setHeight();
        return $parent;
    }

    /**
     * Подсчёт фактора баланса - разности высот потомков
     * @param BinaryNode $node
     * @return int
     */
    protected function bFactor(BinaryNode $node): int
    {
        if (!$node->right && !$node->left)  {
            return 0;
        } elseif (!$node->right || !$node->left) {
            return $node->right ? $node->right->height : -$node->left->height;
        }
        return $node->right->height - $node->left->height;
    }

    /**
     * Поиск в дереве по ключу
     * @param int $value
     * @param BinaryNode $tree
     * @return bool|BinaryNode
     */
    public function findNode(int $value, BinaryNode $tree): bool|BinaryNode
    {
        if (is_null($tree)) {
            return false;
        }

        if ($tree->value->getId() > $value) {
            return $this->findNode($value, $tree->left);
        } elseif ($tree->value->getId() < $value) {
            return $this->findNode($value, $tree->right);
        } else {
            return $tree;
        }
    }

}