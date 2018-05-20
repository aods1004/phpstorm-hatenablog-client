<?php

namespace App\Normalizer;

use App\Entity\{
    Entries, Feed
};
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class AtomPubFeedDenormalizer
 * @package App\Component\Serializer\Normalizer
 */
class AtomPubFeedDenormalizer implements DenormalizerInterface
{

    use AtomPubNormalizerTrait;

    /** @var AtomPubEntryNormalizer */
    protected $entryNormalizer;

    /**
     * AtomPubFeedDenormalizer constructor.
     */
    public function __construct()
    {
        $this->entryNormalizer = new AtomPubEntryNormalizer();
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
     * @return Entries
     */
    public function denormalizeAtomEntries($data): Entries
    {
        $entries= new Entries();
        foreach ($data as $entry) {
            $entries->append($this->denormalizeAtomEntry($entry));
        }
        return $entries;
    }

}