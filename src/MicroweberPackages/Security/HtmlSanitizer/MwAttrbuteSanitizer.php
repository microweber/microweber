<?php


namespace MicroweberPackages\Security\HtmlSanitizer;

use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\TextSanitizer\StringSanitizer;
use Symfony\Component\HtmlSanitizer\TextSanitizer\UrlSanitizer;
use Symfony\Component\HtmlSanitizer\Visitor\AttributeSanitizer\AttributeSanitizerInterface;

class MwAttrbuteSanitizer implements AttributeSanitizerInterface
{
    public function getSupportedElements(): ?array
    {
        // Check all elements for URL attributes
        return null;
    }

    public function getSupportedAttributes(): ?array
    {
        return MwHtmlSanitizerReference::MW_ATTRIBUTES;
    }

    public function sanitizeAttribute(string $element, string $attribute, string $value, HtmlSanitizerConfig $config): ?string
    {
        return StringSanitizer::encodeHtmlEntities($value);


    }
}
