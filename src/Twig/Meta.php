<?php

namespace App\Twig;


/**
 * Class ReversibleTemplate
 * @package App\Twig
 */
class Meta extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $meta = [];

    public function getFunctions()
    {

        return [
            new \Twig_SimpleFunction('app_entry', function ($data) {
                $this->meta = $data;
                return '';
            }),
        ];
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }
}