<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Entry
 * @package App\Entity
 */
class Entry implements AtomPubEntityInterface
{
    use AtomPubEntityTrait;
    /** @var IdInterface */
    protected $id;
    /** @var Content */
    protected $summary;
    /** @var Content */
    protected $content;
    /** @var \DateTimeInterface */
    protected $published;
    /** @var \DateTimeInterface */
    protected $edited;
    /** @var Categories */
    protected $categories;
    /** @var array */
    protected $control;
    /** @var Link */
    protected $editLink;
    /** @var Link */
    protected $alternateLink;
    /** @var string */
    protected $entryIdPrefix;
    /**
     * Entry constructor.
     * @param EntryId $id
     * @param string $title
     * @param array $author
     * @param Summary $summary
     * @param Content $content
     * @param \DateTimeInterface $updated
     * @param \DateTimeInterface $published
     * @param \DateTimeInterface $edited
     * @param Links $links
     * @param Categories $categories
     * @param array $control
     */
    public function __construct(
        EntryId $id,
        string $title,
        array $author,
        Summary $summary,
        Content $content,
        \DateTimeInterface $updated,
        ?\DateTimeInterface $published,
        ?\DateTimeInterface $edited,
        ?Links $links,
        ?Categories $categories,
        array $control
    )
    {
        $this->id = $id;
        $this->published = $published;
        $this->edited = $edited;
        $this->summary = $summary;
        $this->content = $content;
        $this->categories = $categories;
        $this->control = $control;
        foreach ($links ?? [] as $link) {
            foreach ($link->getRels() as $relation) {
                switch ($relation) {
                    case Link::EDIT:
                        $this->editLink = $link;
                        break;
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
     * @return string
     */
    public function getEntryId()
    {
        return $this->getId()->getEntryId();
    }

    /**
     * @return string
     */
    public function getHash()
    {

        return sha1($this->createHashSeed());
    }

    public function createHashSeed()
    {
        $category = array_reduce($this->categories->getCategories(), function($carry, Category $category) {
            return $carry.','.strval($category);
        });

        return
            'title: ' . $this->title
            . 'content: ' . md5(trim($this->content->getValue()))
            . ' update:' . $this->updated->format(DATE_ATOM)
            . ' category: ' . $category
            . ' draft: ' . $this->control['app:draft'];
    }


    /**
     * @return Content
     */
    public function getContent(): Content
    {
        return $this->content;
    }

    /**
     * @return Content
     */
    public function getSummary(): Content
    {
        return $this->summary;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEdited(): ?\DateTimeInterface
    {
        return $this->edited;
    }

    /**
     * @return Links
     */
    public function getLinks(): Links
    {
        return $this->links;
    }

    /**
     * @return Categories
     */
    public function getCategories(): Categories
    {
        return $this->categories;
    }
    /**
     * @return array
     */
    public function getControl(): ?array
    {
        return $this->control;
    }

    /**
     * @return Link
     */
    public function getEditLink(): Link
    {
        return $this->editLink;
    }

    /**
     * @return Link
     */
    public function getAlternateLink(): Link
    {
        return $this->alternateLink;
    }

}