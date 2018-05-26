<?php

namespace App\Encoder;

use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * Class AtomPubEntryXmlEncoder
 * @package App\Encoder
 */
class AtomPubEntryXmlEncoder implements EncoderInterface
{
    use AtomPubEncoderTrait;

    const ENTRY_XML = 'entry_xml';

    protected $twig;

    /**
     * LocalFileEntryEncoder constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param mixed $data
     * @param string $format
     * @param array $context
     * @return bool|float|int|string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function encode($data, $format, array $context = [])
    {
        return  $this->twig->render('entry.xml.twig', [
            'title' => $data['title'],
            'content' => $data['content']['#'],
            'category' => $this->convertCategoryToTermList($data['category'] ?? []),
            'updated' => $data['updated'],
            'draft' => $data['app:control']['app:draft'],
        ]);
    }

    /**
     * @param string $format
     * @return bool
     */
    public function supportsEncoding($format)
    {
        return static::ENTRY_XML === $format;
    }
}