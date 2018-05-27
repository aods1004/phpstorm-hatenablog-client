<?php

namespace App\Encoder;

use App\Twig\AppExtension;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * Class LocalFileEntryEncoder
 * @package App\Encoder
 */
class EntryMarkdownEncoder implements EncoderInterface,DecoderInterface
{
    use AtomPubEncoderTrait;

    const CONTENT_MARKDOWN = 'content_md';
    /** @var \Twig_Environment  */
    protected $twig;
    /** @var string */
    protected $templateDirPath;

    /**
     * EntryMarkdownEncoder constructor.
     * @param \Twig_Environment $twig
     * @param string $templateDirPath
     */
    public function __construct(\Twig_Environment $twig, string $templateDirPath = '')
    {
        $this->twig = $twig;
        $this->templateDirPath = $templateDirPath;
    }

    /**
     * @param $data
     * @param $format
     * @param array $context
     * @return array
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function decode($data, $format, array $context = array())
    {
        $appExtension = new AppExtension($this->templateDirPath);
        $twig = new \Twig_Environment(new \Twig_Loader_Array(['content' => $data]), ['autoescape' => false]);
        $twig->setExtensions([$appExtension]);
        $content = $twig->render('content', []);
        $data = $appExtension->getMeta();
        return [
            'title' => $data['title'] ?? '',
            'content' => ['#' => $content,],
            'category' => $this->convertTermListToCategory($data['category']),
            'updated' => $data['updated'],
            'app:control' => ['app:draft' => $data['draft'],]
        ];
    }

    /**
     * @param $format
     * @return bool
     */
    public function supportsDecoding($format)
    {
        return static::CONTENT_MARKDOWN === $format;
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
        return  $this->twig->render('content.md.twig', [
            'url' => $this->findLinkByRel('alternate', $data['link'] ?? []),
            'content_type' => $data['content']['@type'],
            'title' => $data['title'] ?? '',
            'updated' => $data['updated'] ?? '',
            'category' => $this->convertCategoryToTermList($data['category'] ?? []),
            'content' => $data['content']['#'] ?? '',
            'draft' => $data['app:control']['app:draft'] ?? '',
        ]);
    }

    /**
     * @param string $format
     * @return bool
     */
    public function supportsEncoding($format)
    {
        return static::CONTENT_MARKDOWN === $format;
    }
}