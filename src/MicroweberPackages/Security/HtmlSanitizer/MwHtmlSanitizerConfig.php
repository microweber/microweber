<?php

namespace MicroweberPackages\Security\HtmlSanitizer;

use Symfony\Component\HtmlSanitizer\Reference\W3CReference;

class MwHtmlSanitizerConfig extends \Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig
{
    public function allowStaticElements(): static
    {
        $elements = array_merge(
            array_keys(MwHtmlSanitizerReference::MW_ELEMENTS),
            array_keys(W3CReference::HEAD_ELEMENTS),
            array_keys(W3CReference::BODY_ELEMENTS),

        );

        $clone = clone $this;
        foreach ($elements as $element) {
            $clone = $clone->allowElement($element, '*');
        }

        return $clone;
    }

    public function allowSafeElements(): static
    {
        $attributes = [];
        foreach (W3CReference::ATTRIBUTES as $attribute => $isSafe) {
            if ($isSafe) {
                $attributes[] = $attribute;
            }
        }
        foreach (MwHtmlSanitizerReference::MW_ATTRIBUTES as $attribute => $isSafe) {
            if ($isSafe) {
                $attributes[] = $attribute;
            }
        }

        $clone = clone $this;


//        foreach (W3CReference::HEAD_ELEMENTS as $element => $isSafe) {
//            if ($isSafe) {
//                $clone = $clone->allowElement($element, $attributes);
//            }
//        }
//
//        foreach (W3CReference::BODY_ELEMENTS as $element => $isSafe) {
//            if ($isSafe) {
//                $clone = $clone->allowElement($element, $attributes);
//            }
//        }
//
//        foreach (MwHtmlSanitizerReference::MW_ELEMENTS as $element => $isSafe) {
//            if ($isSafe) {
//                $clone = $clone->allowElement($element, '*');
//            }
//        }


        foreach ($attributes as $attribute) {
            $clone = $clone->allowAttribute($attribute, '*');
        }
        return $clone;
    }
}
