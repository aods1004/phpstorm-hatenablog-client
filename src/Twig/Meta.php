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
            new \Twig_Function('app_entry', function ($data) {
                $this->meta = array_merge($this->meta, $data);
                return '';
            }),
            new \Twig_Function('app_template', function ($name = null) {
                ob_start();
                require __DIR__."/../../../templates/$name.php";
                return '<!-- #auto-generated-by-from -->'
                    . implode('', array_map('trim', explode("\n", ob_get_clean())))
                    . '';
            }),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter('meta_data', function ($value) {
                return str_replace("'", "\'", $value);
                }, ['is_safe' =>['html']]),
            new \Twig_Filter('md_content', function ($value) {
                return strtr($value, [
                    "{{" => "{{ '{{' }}",
                    "}}" => "{{ '}}' }}",
                    "{#" => "{{ '{#' }}",
                    "#}" => "{{ '#}' }}",
                    "{%" => "{{ '{%' }}",
                    "%}" => "{{ '%}' }}",
                ]);
            }, ['is_safe' =>['html']]),
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