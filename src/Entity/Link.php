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
    const NEXT = 'next';
    const EDIT = 'edit';
    const ALTERNATE = 'alternate';

    /** @var string[] */
    private $relations;
    /** @var UriInterface */
    private $uri;
    /** @var string */
    private $type;

    /**
     * Link constructor.
     * @param array $relations
     * @param UriInterface $uri
     * @param string $type
     */
    public function __construct(array $relations, UriInterface $uri, string $type)
    {
        $this->relations = $relations;
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
     * @return array|string[]
     */
    public function getRels()
    {
        return $this->relations;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return array_filter([
            'rel' => $this->relations,
            'content-type' => $this->type,
            'href' => $this->uri,
        ]);
    }

}