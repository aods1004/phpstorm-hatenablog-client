<?php

namespace App\Entity;
/**
 * Class Categories
 * @package App\Entity
 * @method  Category[] getIterator() : iterable;
 * @property Category[] $items;
 */
class Categories implements CollectionInterface
{
    use CollectionTrait;

    /**
     * @param Category $category
     */
    public function append(Category $category): void
    {
        $this->items[] = $category;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->items ?: [];
    }

}