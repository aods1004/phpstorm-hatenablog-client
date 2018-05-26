<?php

namespace App\Normalizer;

use App\Entity\{
    Entries, Entry, Feed, FeedId
};
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class AtomPubFeedDenormalizer
 * @package App\Component\Serializer\Normalizer
 */
class AtomPubFeedDenormalizer implements DenormalizerInterface
{

    use AtomPubNormalizerTrait;

    const CONTEXT_GLOBAL_ENTRIES = 'global_entries';

    /** @var AtomPubEntryNormalizer */
    protected $entryNormalizer;

    /**
     * AtomPubFeedDenormalizer constructor.
     * @param AtomPubEntryNormalizer $entryNormalizer
     */
    public function __construct(AtomPubEntryNormalizer $entryNormalizer)
    {
        $this->entryNormalizer = $entryNormalizer;
    }

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
        $globalEntries = $context[static::CONTEXT_GLOBAL_ENTRIES] ?? null;
        $feed = new Feed(
            new FeedId($data['id'] ?? ''),
            $data['title'] ?? '',
            $data['subtitle'] ?? '',
            $data['author'] ?? [],
            $this->denormalizeAtomEntries($data['entry'] ?? [], $globalEntries),
            $this->denormalizeAtomLinks($data['link'] ?? []),
            \DateTimeImmutable::createFromFormat(DATE_ATOM, $data['updated'])
        );
        return $feed;
    }

    /**
     * @param $data
     * @param Entries|null $globalEntries
     * @return Entries
     */
    public function denormalizeAtomEntries($data, ?Entries $globalEntries = null): Entries
    {
        $entries= new Entries();
        foreach ($data as $entry) {
            $entry = $this->entryNormalizer->denormalize($entry, Entry::class);
            $entries->append($entry);
            if ($globalEntries) {
                $globalEntries->append($entry);
            }
        }
        return $entries;
    }

}