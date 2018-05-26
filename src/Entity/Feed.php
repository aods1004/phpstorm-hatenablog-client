<?php

declare(strict_types=1);

namespace App\Entity;
use Psr\Link\LinkInterface;

/**
 * Class Feed
 * @package App\Entity
 */
class Feed
{
    use AtomPubEntityTrait;

    /** @var IdInterface */
    protected $id;
    /** @var string */
    protected $subtitle;
    /** @var Entries */
    protected $entries = [];
    /** @var Link */
    protected $alternateLink;
    /**
     * Feed constructor.
     * @param FeedId $id
     * @param string $title
     * @param string $subtitle
     * @param array $author
     * @param Entries|null $entries
     * @param Links $links
     * @param \DateTimeInterface|null $updated
     * @param Entry|null $globalEntries
     */
    public function __construct(
        FeedId $id,
        string $title,
        string $subtitle,
        array $author,
        ?Entries $entries,
        Links $links,
        ?\DateTimeInterface $updated,
        ?Entry $globalEntries = null
    )
    {
        $this->id = $id;
        $this->subtitle = $subtitle;
        $this->entries = $entries;
        foreach ($links ?? [] as $link) {
            foreach ($link->getRels() as $relation) {
                switch ($relation) {
                    case Link::ALTERNATE:
                        $this->alternateLink = $link;
                        break;
                }
            }
        }
        $this->setAtomPubEntityCommonInfo($title, $author, $links, $updated);
    }


    /**
     * @return EntryId
     */
    public function getId(): IdInterface
    {
        return $this->id;
    }

    /**
     * @return Links
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return LinkInterface|Link|null
     */
    public function getNextLink()
    {
        $links = $this->getLinks()->getLinksByRel(Link::NEXT);
        return $links[0] ?? null;
    }

    /**
     * @return Entries
     */
    public function getEntries(): Entries
    {
        return $this->entries;
    }

    /**
     * @return Link
     */
    public function getAlternateLink(): Link
    {
        return $this->alternateLink;
    }
}