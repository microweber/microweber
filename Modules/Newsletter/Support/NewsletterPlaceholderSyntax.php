<?php

namespace Modules\Newsletter\Support;

class NewsletterPlaceholderSyntax
{
    public static function defaultCampaignBody(): string
    {
        return 'Hello, [[name]]! <br />How are you today?';
    }

    public static function basicHelperText(): string
    {
        return 'You can use the following variables: [[name]], [[email]], [[unsubscribe_url]]';
    }

    /**
     * Normalize legacy curly-brace placeholders and the new square-bracket
     * syntax to Twig variables before rendering the newsletter body.
     */
    public static function normalizeTwigVariables(string $text, array $variables): string
    {
        foreach ($variables as $variable) {
            $text = str_replace('[['.$variable.']]', '{{ '.$variable.' }}', $text);
            $text = preg_replace('/\{\{\s*'.preg_quote($variable, '/').'\s*\}\}/', '{{ '.$variable.' }}', $text) ?? $text;
        }

        return $text;
    }

    public static function replaceTokenVariants(string $text, string $token, string $value): string
    {
        $text = str_replace('[['.$token.']]', $value, $text);

        return preg_replace('/\{\{\s*'.preg_quote($token, '/').'\s*\}\}/', $value, $text) ?? $text;
    }
}
