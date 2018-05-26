<?php

declare(strict_types=1);

namespace App\Entity;

class ContentType
{
    const HATENA_SYNTAX = 'text/x-hatena-syntax';
    const MARKDOWN = 'text/x-markdown';
    const HTML = 'text/html';
    const TEXT = 'text';

    const EXTENSION_MAP = [
        self::HATENA_SYNTAX => '.md',
        self::MARKDOWN => '.md',
        self::HTML => '.html',
        self::TEXT => '.txt',
    ];
    const TYPENAME_MAP = [
        self::HATENA_SYNTAX => 'markdown',
        self::MARKDOWN => 'markdown',
        self::HTML => 'html',
        self::TEXT => 'text',
    ];

    /** @var string */
    protected $value;

    /**
     * ContentType constructor.
     * @param null|string $value
     */
    public function __construct(?string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return static::EXTENSION_MAP[$this->value] ?? static::EXTENSION_MAP[static::TEXT];
    }
    /**
     * @return string
     */
    public function getTypeName()
    {
        return static::TYPENAME_MAP[$this->value] ?? static::EXTENSION_MAP[static::TEXT];
    }
}