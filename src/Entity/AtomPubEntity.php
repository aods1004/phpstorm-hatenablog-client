<?php

namespace App\Entity;


abstract class  AtomPubEntity
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

    /**
     * AtomPubEntityTrait constructor.
     * @param string $id
     * @param string $title
     * @param Links $links
     * @param \DateTimeInterface $updated
     * @param array $author
     */
    public function __construct(
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
    public function getUpdated(): \DateTimeInterface
    {
        return $this->updated;
    }
}