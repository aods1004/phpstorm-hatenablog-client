<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Content
 * @package App\Entity
 */
class Content
{
    /** @var ContentType */
    protected $type;
    /** @var string|null */
    protected $value;

    /**
     * Content constructor.
     * @param ContentType $type
     * @param null|string $value
     */
    public function __construct(ContentType $type, ?string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
    /**
     * @return ContentType
     */
    public function getType(): ?ContentType
    {
        return $this->type;
    }
}