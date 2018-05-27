<?php

namespace App\Normalizer;

use App\Entity\{
    Entry, EntryId, Link
};
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AtomPubEntryNormalizer
 * @package App\Component\Serializer\Normalizer
 */
class AtomPubEntryNormalizer implements NormalizerInterface, DenormalizerInterface
{
    use AtomPubNormalizerTrait;

    /** @var string */
    protected $entryIdPrefix;
    /** @var string */
    protected $hatenaBlogEntryEditLink;

    /**
     * AtomPubEntryNormalizer constructor.
     * @param string $entryIdPrefix
     * @param string $hatenaBlogEntryEditLink
     */
    public function __construct(string $entryIdPrefix = '', string $hatenaBlogEntryEditLink = '')
    {
        $this->entryIdPrefix = $entryIdPrefix;
        $this->hatenaBlogEntryEditLink = $hatenaBlogEntryEditLink;


    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return is_a($data, Entry::class);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type == Entry::class;
    }

    /**
     * @param object $object
     * @param null $format
     * @param array $context
     * @return array|bool|float|int|string
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /** @type Entry $object */
        return array_filter([
            'id' => strval($object->getId()),
            'title' => $object->getTitle(),
            'content' => array_filter([
                '#' => (string)$object->getContent()->getValue(),
                '@type' => (string)$object->getContent()->getType(),
            ]),
            'updated' => $this->normalizeDatetime($object->getUpdated()),
            'published' => $this->normalizeDatetime($object->getPublished()),
            'app:edited' => $this->normalizeDatetime($object->getEdited()),
            'category' => $this->normalizeCategory($object->getCategories()),
            'app:control' => $object->getControl(),
            'link' => $this->normalizeLink($object->getLinks()),
        ]);
    }

    /**
     * @param mixed $data
     * @param string $class
     * @param null $format
     * @param array $context
     * @return Entry
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $remoteEntry = $context['remote_entry'] ?? null;
        if ($remoteEntry instanceof Entry) {
            $data['link'] = $this->normalizeLink($remoteEntry->getLinks());
            $data['id'] = strval($remoteEntry->getId());
            $data['published'] = $this->normalizeDatetime($remoteEntry->getPublished());
        }
        if ($context['edited'] ?? null) {
            $data['app:edited'] = $context['edited'];
        }
        return $this->denormalizeAtomEntry($data);
    }

    /**
     * @param $entry
     * @return Entry
     */
    protected function denormalizeAtomEntry($entry)
    {
        $entryId = new EntryId($entry['id'], $this->entryIdPrefix);
        return new Entry(
            $entryId,
            $entry['title'],
            $entry['author'] ?? [],
            $this->denormalizeAtomSummary($entry['summary'] ?? []),
            $this->denormalizeAtomContent($entry['content'] ?? []),
            \DateTimeImmutable::createFromFormat(DATE_ATOM, $entry['updated']),
            \DateTimeImmutable::createFromFormat(DATE_ATOM, $entry['published']),
            \DateTimeImmutable::createFromFormat(DATE_ATOM, $entry['app:edited']),
            $this->denormalizeAtomLinks(
                array_merge(
                    $entry['link'] ?? [],
                    $this->createEditAlternateLink($entryId)
                )
            ),
            $this->denormalizeAtomCategory($entry['category'] ?? []),
            $entry['app:control'] ?? []
        );
    }

    /**
     * @param EntryId $entryId
     * @return array
     */
    protected function createEditAlternateLink(EntryId $entryId)
    {
        return [
            [
                '@rel' => [Link::ALTERNATE_EDIT],
                '@href' => $this->hatenaBlogEntryEditLink . '?entry=' . $entryId->getEntryId(),
                '@type' => 'html',
            ]
        ];
    }
}