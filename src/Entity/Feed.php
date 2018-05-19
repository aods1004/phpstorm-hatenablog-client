<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Feed
 * @package App\Entity
 */
class Feed extends AtomPubEntity
{
    /** @var string */
    protected $subtitle;
    /** @var Entries */
    protected $entries = [];

    /**
     * Feed constructor.
     * @param string $id
     * @param string $title
     * @param string $subtitle
     * @param array|null $author
     * @param Entries|null $entries
     * @param Links|null $links
     * @param \DateTimeInterface|null $updated
     */
    public function __construct(
        string $id,
        string $title,
        string $subtitle,
        array $author,
        ?Entries $entries,
        Links $links,
        ?\DateTimeInterface $updated
    )
    {
        $this->subtitle = $subtitle;
        $this->entries = $entries;
        parent::__construct($id, $title, $author, $links, $updated);
    }

    /**
     * @return Links
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return Entries
     */
    public function getEntries(): Entries
    {
        return $this->entries;
    }
}