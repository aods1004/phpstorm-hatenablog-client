<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class CollectionInterface
 * @package App\Entity\Collection
 */
interface CollectionInterface extends \IteratorAggregate
{
    /**
     * @return iterable
     */
    public function getIterator(): iterable;
}