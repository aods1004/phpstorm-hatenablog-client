<?php

namespace App\Normalizer;

use App\Entity\Categories;
use App\Entity\Category;
use App\Entity\Content;
use App\Entity\ContentType;
use App\Entity\Entry;
use App\Entity\Link;
use App\Entity\Links;
use App\Entity\Summary;
use function GuzzleHttp\Psr7\uri_for;

/**
 * Trait AtomPubNormalizerTrait
 * @package App\Normalizer
 */
trait AtomPubNormalizerTrait
{
    /**
     * @param Categories $categories
     * @return array
     */
    protected function normalizeCategory(Categories $categories)
    {
        $retVal = [];
        foreach ($categories as $category) {
            $retVal[] = [
                '@term' => strval($category),
                '#' => '',
            ];
        }
        return $retVal;
    }

    /**
     * @param Links $links
     * @return array
     */
    protected function normalizeLink(Links $links): array
    {
        $retVal = [];
        foreach ($links as $link) {
            $retVal[] = [
                '@href' => (string) $link->getHref(),
                '@rel' => $link->getRels(),
            ];
        }
        return $retVal;
    }

    /**
     * @param \DateTimeInterface|null $dateTime
     * @param $format
     * @return string|null
     */
    protected function normalizeDatetime(?\DateTimeInterface $dateTime, $format = DATE_ATOM)
    {
        if ($dateTime instanceof \DateTimeInterface) {
            return $dateTime->format($format);
        }
        return null;
    }

    /**
     * @param $entry
     * @return Entry
     */
    public function denormalizeAtomEntry($entry)
    {
        return new Entry(
            $entry['id'],
            $entry['title'],
            $entry['author'] ?? [],
            $this->denormalizeAtomSummary($entry['summary'] ?? []),
            $this->denormalizeAtomContent($entry['content'] ?? []),
            \DateTimeImmutable::createFromFormat(DATE_ATOM,$entry['updated']),
            \DateTimeImmutable::createFromFormat(DATE_ATOM, $entry['published']),
            \DateTimeImmutable::createFromFormat(DATE_ATOM, $entry['app:edited']),
            $this->denormalizeAtomLinks($entry['link'] ?? []),
            $this->denormalizeAtomCategory($entry['category'] ?? []),
            $entry['app:control'] ?? []
        );
    }


    /**
     * @param $summary
     * @return Summary
     */
    private function denormalizeAtomSummary(array $summary)
    {
        return new Summary($summary['@type'] ?? '', $summary['#'] ?? '');
    }

    /**
     * @param $content
     * @return Content
     */
    private function denormalizeAtomContent($content)
    {
        return new Content(new ContentType($content['@type'] ?? ''), trim($content['#'] ?? ''));
    }

    /**
     * @param $data
     * @return Links
     */
    private function denormalizeAtomLinks(array $data) {
        $links = new Links();
        foreach ($data as $value) {
            $links->append(new Link(
                is_array($value['@rel'] ?? []) ? $value['@rel'] ?? [] : [$value['@rel']],
                uri_for($value['@href'] ?? ''),
                $value['@type'] ?? ''
            ));
        }
        return $links;
    }

    /**
     * @param array $data
     * @return Categories
     */
    private function denormalizeAtomCategory(array $data)
    {
        if (isset($data['@term'])) {
            $data = [$data];
        }
        $categories = new Categories();
        foreach ($data as $value) {
            $categories->append(new Category($value['@term'] ?? ''));
        }
        return $categories;
    }
}