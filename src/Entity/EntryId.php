<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Interface IdInterface
 */
class EntryId implements IdInterface
{
    /** @var string */
    protected $id;
    /** @var string */
    protected $entryIdPrefix;

    /**
     * EntryId constructor.
     * @param $id
     * @param string $entryIdPrefix
     */
    public function __construct($id, $entryIdPrefix = '')
    {
        $this->id = $id;
        $this->entryIdPrefix = $entryIdPrefix;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEntryId()
    {
        return str_replace($this->entryIdPrefix, '', trim($this->id));
    }
}