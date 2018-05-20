<?php

namespace App\Twig;


/**
 * Class ReversibleTemplate
 * @package App\Twig
 */
class ReversibleTemplate extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('app_template', function ($name = null) {
                ob_start();
                require __DIR__."/../../../templates/$name.php";
                return '<!-- #generated-by-local -->' . implode('', array_map('trim', explode("\n", ob_get_clean())));
            }),
        ];
    }
}