<?php

declare(strict_types=1);

namespace App\Entity;

use Psr\Http\Message\UriInterface;
use Psr\Link\LinkInterface;

/**
 * Class Link
 * @package App\Entity
 */
class Link implements LinkInterface
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
     * @return UriInterface|string
     */
    public function getHref()
    {
        return $this->uri;
    }

    /**
     * @return bool
     */
    public function isTemplated()
    {
        return false;
    }

    /**
     * @return string|string[]
     */
    public function getRels()
    {
        return [$this->relation];
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return array_filter([
            'rel' => $this->relation,
            'type' => $this->type,
            'href' => $this->uri,
        ]);
    }

}