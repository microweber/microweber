<?php

namespace MicroweberPackages\OpenApi\Models;


class AnnotationParser
{
    const DOCBLOCK_PATTERN = '%\/\*\*.*\*\/%s';
    const ANNOTATION_PATTERN = '/(?:\*\s*\@)(?P<tag>[a-zA-Z]+)\s(?P<value>.+)\n/';
    const DESCRIPTION_PATTERN = '/\s*\*\s*(?P<description>[^@\/\s\*].+)/';

    /**
     * Verify if the string has a docBlock in it.
     *
     * @param string $block
     * @return bool
     */
    public function hasDocBlock($block)
    {
        if (!is_string($block) || $block == '') {
            return false;
        }

        return (bool)preg_match(self::DOCBLOCK_PATTERN, $block);
    }

    /**
     * Extract annotations from a docBlock
     * @param string $text
     * @return array
     */
    public function getAnnotations($text)
    {
        $annotations = array();

        if (!$this->hasDocBlock($text)) {
            return $annotations;
        }

        preg_match_all(self::ANNOTATION_PATTERN, $text, $matches);

        foreach ($matches['tag'] as $index => $tag) {
            $annotations[$tag] = trim($matches['value'][$index]);
        }

        return $annotations;
    }

    /**
     * Retrieves any text that is not a * follow by a space or an annotation
     * @param string $text
     * @return string
     */
    public function getDescription($text)
    {
        $description = '';

        if (!$this->hasDocBlock($text)) {
            return $description;
        }

        preg_match_all(self::DESCRIPTION_PATTERN, $text, $matches);

        return implode(" ", $matches['description']);
    }

    /**
     * Separate the docBlock from the content
     * @param string $text
     * @return array
     */
    public function extractDocBlock($text)
    {
        $split = array(
            'meta' => '',
            'content' => '',
        );

        if (!is_string($text) || !$this->hasDocBlock($text)) {
            $split['content'] = $text;
        } else {
            $split['meta'] = substr($text, 0, strpos($text, '*/') + 2);
            $split['content'] = trim(substr($text, strpos($text, '*/') + 2));
        }

        return $split;
    }

    /**
     * Parses a string for a single
     * @param string $text
     * @return array
     */
    public function parse($text)
    {
        $data = array(
            'meta' => array(),
            'content' => ''
        );

        if (!is_string($text) || !$this->hasDocBlock($text)) {
            $data['content'] = $text;
        } else {
            $split = $this->extractDocBlock($text);
            $data['meta'] = $this->getAnnotations($split['meta']);
            $data['meta']['description'] = $this->getDescription($split['meta']);
            $data['content'] = $split['content'];
        }


        return $data;
    }
}