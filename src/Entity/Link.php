<?php

declare(strict_types=1);

namespace App\Entity;

use Psr\Http\Message\UriInterface;

/**
 * Class Link
 * @package App\Entity
 */
class Link
{
    const RELATION_NEXT = 'next';
    /** @var string */
    private $relation;
    /** @var UriInterface */
    private $uri;
    /** @var string */
    private $type;

    /**
     * Link constructor.
     * @param string $relation
     * @param UriInterface $uri
     * @param string $type
     */
    public function __construct(string $relation, UriInterface $uri, string $type)
    {
        $this->relation = $relation;
        $this->uri = $uri;
        $this->type = $type;
    }

    /**
     * @return UriInterface
     */
    public function getNextUri()
    {
        if ($this->relation === static::RELATION_NEXT) {
            return $this->uri;
        }
        return null;
    }
}