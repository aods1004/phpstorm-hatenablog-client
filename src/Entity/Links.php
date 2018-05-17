<?php

declare(strict_types=1);

namespace App\Entity;
/**
 * Class Links
 * @package App\Entity
 */
class Links implements \IteratorAggregate
{
    /** @var Link[] */
    protected $link = [];

    /**
     * @param Link $link
     */
    public function append(Link $link)
    {
        $this->link[] = $link;
    }

    /**
     * @return Link[]|iterable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->link);
    }

}