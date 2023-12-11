<?php

namespace App\Services\Binary;

use Symfony\Component\Config\Definition\Exception\Exception;

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
     * @param int $value
     * @param mixed|null $data
     */
    public function insert(int $value, mixed $data = null)
    {
        $node = new BinaryNode($value, $data);
        $this->insertNode($node, $this->root);
    }

    /**
     * Данные выводимые из узла
     * @param BinaryNode $root
     * @return mixed
     */
    protected function showNode(BinaryNode $root): mixed
    {
        return $root->value;
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
        $array = [$this->showNode($root)];
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
            $array[$deep][] = 'null';
            return $array;
        }
        $array[$deep][] = $this->showNode($root);
        $deep++;
        $this->showByStage($root->left, $deep, $array);
        $this->showByStage($root->right, $deep, $array);
        return $array;
    }

    public function searchMaxBranch(int $value, BinaryNode $binaryNode): string
    {
        $node = $this->findNode($value, $binaryNode);

        $right = $node->right->sum();
        $left = $node->left->sum();
        return $right > $left ? 'Right, summ = ' . $right : 'Left, summ =' . $left;
    }


    /**
     * Процесс вставки в дерево
     * @param BinaryNode $node
     * @param BinaryNode|null $subtree
     * @param BinaryNode|null $parent
     * @return $this
     */
    protected function insertNode(BinaryNode $node, ?BinaryNode &$subtree, ?BinaryNode &$parent = null): static
    {
        if (is_null($subtree)) {
            $subtree = $node;
            if (!$this->root) {
                $this->root = $node;
            } else {
                $subtree->parent = $parent;
            }
        } else {
            if ($node->value < $subtree->value) {
                $this->insertNode($node, $subtree->left, $subtree);
            } elseif ($node->value > $subtree->value) {
                $this->insertNode($node, $subtree->right, $subtree);
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
        } else {
            $parent->parent = $node->parent;
            if ($node->parent->right === $node) {
                $node->parent->right = $parent;
            } else {
                $node->parent->left = $parent;
            }
        }
        $node->left = $parent->right;
        if ($parent->right) {
            $parent->right->parent = $node;
        }
        $node->parent = $parent;
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
            $parent->parent = null;
        } else {
            $parent->parent = $node->parent;
            if ($node->parent->right === $node) {
                $node->parent->right = $parent;
            } else {
                $node->parent->left = $parent;
            }
        }
        $node->right = $parent->left;
        if($parent->left) {
            $parent->left->parent = $node;
        }
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
    protected function findNode(int $value, BinaryNode $tree): bool|BinaryNode
    {
        if (is_null($tree)) {
            return false;
        }

        if ($tree->value > $value) {
            return $this->findNode($value, $tree->left);
        } elseif ($tree->value < $value) {
            return $this->findNode($value, $tree->right);
        } else {
            return $tree;
        }
    }

    protected function updateParent(BinaryNode &$node, BinaryNode $newChild = null) {
        $parent = $node->parent;
        if (!$parent) {
            return;
        }
        if ($parent->left === $node) {
            $parent->left = $newChild;
        } else {
            $parent->right = $newChild;
        }
    }

    protected function deleteNode(BinaryNode &$node)
    {
        if (is_null($node->left) && is_null($node->right)) {
            $this->updateParent($node);
            $node = NULL;
        } elseif (is_null($node->left)) {
            $this->updateParent($node, $node->right);
            $node = $node->right;
        } elseif (is_null($node->right)) {
            $this->updateParent($node, $node->right);
            $node = $node->left;
        } else {
            if (is_null($node->right->left)) {
                $node->right->left = $node->left;
                $this->updateParent($node, $node->right);
                $node = $node->right;
            } else {
                $node->value = $node->right->left->value;
                $node->data = $node->right->left->data;
                $this->deleteNode($node->right->left);
            }
        }
    }

    public function delete($value): static
    {
        if ($this->isEmpty()) {
            throw new Exception('Tree is empty!');
        }
        $node = $this->findNode($value, $this->root);
//        dd($node);
        if ($node) {
            $this->deleteNode($node);
        }
        return $this;
    }

}