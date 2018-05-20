<?php

namespace App\Encoder;

use App\Twig\Meta;
use App\Twig\ReversibleTemplate;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class LocalFileEntryEncoder implements EncoderInterface,DecoderInterface
{
    const CONTENT_MARKDOWN = 'content_md';

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
        $meta = new Meta();
        $twig = new \Twig_Environment(new \Twig_Loader_Array(['content' => $data]), ['autoescape' => false]);
        $twig->setExtensions([new ReversibleTemplate(), $meta]);
        $content = $twig->render('content', []);
        $data = $meta->getMeta();

        return [
            'content' => [
                '#' => $content,
            ],
            'category' => array_map(function ($data) {
                return ['#' => '', '@term' => $data];
            }, $data['category'] ?? []),
            'updated' => $data['updated'],
            'control' => [
                'app:draft' => $data['draft'],
            ]
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
            'title' => $data['title'] ?? '',
            'updated' => $data['updated'] ?? '',
            'category' => array_map(function (array $value) {
                return strval($value['@term'] ?? '');
            }, $data['category'] ?? []),
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