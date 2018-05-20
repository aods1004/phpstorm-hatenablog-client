<?php

namespace App\Normalizer;

use App\Entity\{
    Entry
};
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AtomPubEntryNormalizer
 * @package App\Component\Serializer\Normalizer
 */
class AtomPubEntryNormalizer implements NormalizerInterface,DenormalizerInterface
{
    use AtomPubNormalizerTrait;

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return is_a($data,Entry::class);
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
        return [
            'id' => $object->getId(),
            'title' => $object->getTitle(),
            'content' => array_filter([
                '#' => (string) $object->getContent()->getValue(),
                '@type' => (string) $object->getContent()->getType(),
            ]),
            'updated' => $object->getUpdated()->format(DATE_ATOM),
            'published' => $object->getPublished()->format(DATE_ATOM),
            'edited' => $object->getEdited()->format(DATE_ATOM),
            'saved' => date(DATE_ATOM),
            'category' => $this->normalizeCategory($object->getCategories()),
            'control' => $object->getControl(),
            'link' => $this->normalizeLink($object->getLinks()),
        ];
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
        return $this->denormalizeAtomEntry($data);
    }

}