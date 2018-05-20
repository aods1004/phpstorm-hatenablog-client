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
    /** @var Content */
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
    /** @var Categories */
    protected $categories;
    /** @var array */
    protected $control;


    /**
     * Entry constructor.
     * @param string $id
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
        string $id,
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
        $this->published = $published;
        $this->edited = $edited;
        $this->summary = $summary;
        $this->content = $content;
        $this->categories = $categories;
        $this->control = $control;
        parent::__construct($id, $title, $author, $links, $updated);
    }

    /**
     * @return string
     */
    public function getHash()
    {
        $category = array_reduce($this->categories->getCategories(), function($carry, Category $category) {
            return $carry.':'.strval($category);
        });
        return sha1(
            trim($this->content->getValue()) . $this->updated->format(DATE_ATOM) . $category);
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
    public function getPublished(): \DateTimeInterface
    {
        return $this->published;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEdited(): \DateTimeInterface
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
}