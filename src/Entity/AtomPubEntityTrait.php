<?php

namespace App\Entity;

/**
 * Class AtomPubEntity
 * @package App\Entity
 */
trait AtomPubEntityTrait
{
    /** @var string */
    protected $id;
    /** @var string */
    protected $title;
    /** @var Links */
    protected $links;
    /** @var \DateTimeInterface */
    protected $updated;
    /** @var array */
    protected $author = [];
    /** @var Link */
    protected $alternateLink;

    /**
     * AtomPubEntityTrait constructor.
     * @param string $id
     * @param string $title
     * @param Links $links
     * @param \DateTimeInterface $updated
     * @param array $author
     */
    public function setAtomPubEntityCommonInfo(
        string $id,
        string $title,
        ?array $author,
        ?Links $links,
        ?\DateTimeInterface $updated
    )
    {
        $this->id = $id;
        $this->links = $links;
        $this->title = $title;
        $this->updated = $updated;
        $this->author = $author;

        foreach ($links ?? [] as $link) {
            foreach ($link->getRels() as $relation) {
                switch ($relation) {
                    case Link::ALTERNATE:
                        $this->alternateLink = $link;
                        break;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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

    /**
     * @return Link
     */
    public function getAlternateLink(): Link
    {
        return $this->alternateLink;
    }
}