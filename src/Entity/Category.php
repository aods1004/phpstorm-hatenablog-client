<?php

namespace App\Entity;
/**
 * Class Category
 * @package App\Entity
 */

class Category
{
    /** @var string */
    protected $term;

    /**
     * Category constructor.
     * @param string $term
     */
    public function __construct(string $term)
    {
        $this->term = $term;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->term;
    }
}