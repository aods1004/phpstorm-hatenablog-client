<?php

declare(strict_types=1);

namespace App\Entity;
use Psr\Link\LinkInterface;
use Psr\Link\LinkProviderInterface;

/**
 * Class Links
 * @package App\Entity
 */
class Links implements \IteratorAggregate,LinkProviderInterface
{
    /** @var Link[] */
    protected $links = [];

    /**
     * @return Link[]|LinkInterface[]|\Traversable
     */
    public function getLinks()
    {
       return $this->links;
    }

    /**
     * @param string $target
     * @return Link[]|LinkInterface[]|\Traversable
     */
    public function getLinksByRel($target)
    {
        $response = [];
        foreach ($this->links as $link) {
            foreach($link->getRels() as $rel) {
                if ($rel === $target) {
                    $response[] = $link;
                }
            }
        }
        return $response;
    }

    /**
     * @param Link $link
     */
    public function append(Link $link)
    {
        $this->links[] = $link;
    }

    /**
     * @return Link[]|iterable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->links);
    }




}