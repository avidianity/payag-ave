<?php

namespace App\Services;

use App\Exceptions\Services\DOMServiceException;
use DOMDocument;

class DOMService
{
    /**
     * Strip `<script>` tags from html
     *
     * @param string $html
     * @return string
     */
    public function clean($html)
    {
        $dom = app(DOMDocument::class);

        if (!$dom->loadHTML($html)) {
            throw new DOMServiceException('Unable to load HTML.');
        }

        $scripts = $dom->getElementsByTagName('script');

        $removeable = collect();

        foreach ($scripts as $script) {
            $removeable->push($script);
        }

        $forms = $dom->getElementsByTagName('form');

        foreach ($forms as $form) {
            $removeable->push($form);
        }

        $buttons = $dom->getElementsByTagName('button');

        foreach ($buttons as $button) {
            $removeable->push($button);
        }

        $inputs = $dom->getElementsByTagName('input');

        foreach ($inputs as $input) {
            $removeable->push($input);
        }

        $selects = $dom->getElementsByTagName('select');

        foreach ($selects as $select) {
            $removeable->push($select);
        }

        foreach ($removeable as $element) {
            $element->parentNode->removeChild($element);
        }

        if ($result = $dom->saveHTML()) {
            return $result;
        }

        throw new DOMServiceException('Unable to save as HTML string.');
    }

    /**
     * Checks if specified tags exist in html
     *
     * @param string $html
     * @param string[] $tags
     * @return bool
     */
    public function hasTags($html, $tags)
    {
        $dom = app(DOMDocument::class);

        $dom->loadHTML($html);

        $allExist = true;

        foreach ($tags as $tag) {
            if ($dom->getElementsByTagName($tag)->length === 0) {
                $allExist = false;
                break;
            }
        }

        return $allExist;
    }

    /**
     * Checks if a tag exists in html
     *
     * @param string $html
     * @param string $tag
     * @return bool
     */
    public function hasTag($html, $tag)
    {
        $dom = app(DOMDocument::class);

        $dom->loadHTML($html);

        return $dom->getElementsByTagName($tag)->length > 0;
    }
}
