<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Entries
 * @package App\Entity
 *
 * @method  Entry[] getIterator() : iterable;
 * @property Entry[] $items;
 */
class Entries implements CollectionInterface
{
    use CollectionTrait;

    /**
     * @param Entry $entry
     */
    public function append(Entry $entry): void
    {
        $this->items[strval($entry->getId())] = $entry;
    }
}