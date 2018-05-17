<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Entry
 * @package App\Entity
 */
class Entry extends AtomPubEntity
{
    /** @var string */
    protected $id;
    /** @var string */
    protected $title;
    /** @var string */
    protected $summary;
    /** @var Content */
    protected $content;
    /** @var \DateTimeInterface */
    protected $updated;
    /** @var \DateTimeInterface */
    protected $published;
    /** @var \DateTimeInterface */
    protected $edited;
    /** @var Links */
    protected $links;

    /**
     * Entry constructor.
     * @param string $id
     * @param string $title
     * @param array $author
     * @param Content $summary
     * @param Content $content
     * @param \DateTimeInterface $updated
     * @param \DateTimeInterface $published
     * @param \DateTimeInterface $edited
     * @param Links $links
     */
    public function __construct(
        string $id,
        string $title,
        array $author,
        Content $summary,
        Content $content,
        ?\DateTimeInterface $updated,
        ?\DateTimeInterface $published,
        ?\DateTimeInterface $edited,
        ?Links $links
    )
    {
        $this->published = $published;
        $this->edited = $edited;
        $this->summary = $summary;
        $this->content = $content;
        parent::__construct($id, $title, $author, $links, $updated);
    }


}