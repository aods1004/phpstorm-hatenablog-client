<?php

namespace App\Entity;

/**
 * Class AtomPubEntity
 * @package App\Entity
 */
trait AtomPubEntityTrait
{
    /** @var string */
    protected $title;
    /** @var Links */
    protected $links;
    /** @var \DateTimeInterface */
    protected $updated;
    /** @var array */
    protected $author = [];

    /**
     * AtomPubEntityTrait constructor.
     * @param string $title
     * @param Links $links
     * @param \DateTimeInterface $updated
     * @param array $author
     */
    public function setAtomPubEntityCommonInfo(
        string $title,
        ?array $author,
        ?Links $links,
        ?\DateTimeInterface $updated
    )
    {
        $this->links = $links;
        $this->title = $title;
        $this->updated = $updated;
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }
}