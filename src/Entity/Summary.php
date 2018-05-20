<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Content
 * @package App\Entity
 */
class Summary
{
    /** @var string|null */
    protected $type;
    /** @var string|null */
    protected $value;

    /**
     * Content constructor.
     * @param string $type
     * @param string $value
     */
    public function __construct(?string $type, ?string $value)
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
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return md5($this->value);
    }
}