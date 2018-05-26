<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Interface IdInterface
 */
class FeedId implements IdInterface
{
    /** @var string */
    protected $id;

    /**
     * FeedId constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
}