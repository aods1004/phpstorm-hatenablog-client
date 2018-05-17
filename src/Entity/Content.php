<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Content
 * @package App\Entity
 */
class Content
{
    /** @var string|null */
    protected $type;
    /** @var string|null */
    protected $body;

    /**
     * Content constructor.
     * @param string $type
     * @param string $body
     */
    public function __construct(?string $type, ?string $body)
    {
        $this->type = $type;
        $this->body = $body;
    }
}