<?php

namespace App\Component\Serializer\Normalizer;

use App\Entity\{
    Content, Entries, Entry, Feed, Link, Links
};
use function GuzzleHttp\Psr7\uri_for;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class FeedNormalizer
 * @package App\Component\Serializer\Normalizer
 */
class AtomFeedNormalizer implements DenormalizerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type == Feed::class;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $feed = new Feed(
            $data['id'] ?? '',
            $data['title'] ?? '',
            $data['subtitle'] ?? '',
            $data['author'] ?? [],
            $this->denormalizeAtomEntries($data['entry'] ?? []),
            $this->denormalizeAtomLinks($data['link'] ?? []),
            $this->denormalizeAtomDatetime($data['updated'] ?? null)
        );
        return $feed;
    }
    /**
     * @param $data
     * @param array $context
     * @return Entries
     */
    public function denormalizeAtomEntries($data): Entries
    {
        $entriesEntity = new Entries();
        foreach ($data as $entry) {
            $entryEntity = new Entry(
                $entry['id'] ?? '',
                $entry['title'] ?? '',
                $entry['author'] ?? [],
                new Content($entry['summary']['@type'] ?? '', $entry['summary']['#'] ?? ''),
                new Content($entry['content']['@type'] ?? '', $entry['content']['#'] ?? ''),
                $this->denormalizeAtomDatetime($entry['updated'] ?? ''),
                $this->denormalizeAtomDatetime($entry['published'] ?? ''),
                $this->denormalizeAtomDatetime($entry['edited'] ?? ''),
                $this->denormalizeAtomLinks($entry['link'] ?? [])
            );
            $entriesEntity->append($entryEntity);
        }
        return $entriesEntity;
    }
    /**
     * @param $data
     * @return Links
     */
    private function denormalizeAtomLinks(array $data) {
        $links = new Links();
        foreach ($data as $value) {
            $links->append(new Link(
                $value['@rel'] ?? '',
                uri_for($value['@href'] ?? ''),
                $value['@type'] ?? ''
            ));
        }
        return $links;
    }
    /**
     * @param string|null $value
     * @return \DateTimeImmutable|null
     */
    public function denormalizeAtomDatetime(?string $value)
    {
        return $value ? \DateTimeImmutable::createFromFormat(\DateTimeImmutable::ATOM, $value) : null;
    }
}