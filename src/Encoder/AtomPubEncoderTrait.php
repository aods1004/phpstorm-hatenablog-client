<?php

namespace App\Encoder;

/**
 * Trait AtomPubEncoderTrait
 * @package App\Encoder
 */
trait AtomPubEncoderTrait
{
    /**
     * @param $target
     * @param $links
     * @return string
     */
    protected function findLinkByRel($target, $links)
    {
        foreach ($links as $link) {
            $relations = $link['@rel'] ?? [];
            foreach ($relations as $relation) {
                if ($relation == $target) {
                    return $link['@href'];
                }
            }
        }
        return '';
    }

    /**
     * @param array $category
     * @return array
     */
    protected function convertCategoryToTermList($category = [])
    {

        return array_map(function (array $value) {
            return strval($value['@term'] ?? '');
        }, $category);
    }

    /**
     * @param array $terms
     * @return array
     */
    protected function convertTermListToCategory($terms = [])
    {
        return array_map(function (?string $value) {
            return ['#' => '', '@term' => $value];
        }, $terms);
    }
}