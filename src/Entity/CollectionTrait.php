<?php

namespace App\Entity;

/**
 * Trait CollectionTrait
 * @package App\Entity\Collection
 */
trait CollectionTrait
{
    /** @var array */
    protected $items;

    /**
     * @return \Traversable
     */
    public function getIterator() : iterable
    {
        return new \ArrayIterator($this->items ?: []);
    }
}