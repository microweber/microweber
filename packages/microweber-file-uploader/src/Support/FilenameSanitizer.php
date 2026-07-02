<?php

namespace MicroweberPackages\FileUploader\Support;

/**
 * Sanitizes filenames for safe storage.
 */
class FilenameSanitizer
{
    /**
     * Clean a filename for safe storage.
     * Keeps the original extension, sanitizes only the name part.
     */
    public function sanitize(string $fileName): string
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $nameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);

        // Transliterate and clean
        $clean = $this->transliterate($nameWithoutExt);
        $clean = preg_replace('/\s+\d+%|\)/', '', $clean);
        $clean = preg_replace("/[\/\&%#\$]/", "_", $clean);
        $clean = preg_replace("/[\"\']/", " ", $clean);
        $clean = str_replace(['(', ')', "'", "!", "`", "*", "#", "<", ">"], '-', $clean);
        $clean = str_replace(' ', '-', $clean);
        $clean = str_replace('..', '-', $clean);
        $clean = strtolower($clean);

        // Remove any remaining unsafe characters
        $clean = preg_replace('/[^a-z0-9\-_]/', '', $clean);

        // Ensure non-empty
        if (empty($clean)) {
            $clean = 'file';
        }

        return $clean . '.' . $extension;
    }

    /**
     * Generate a unique filename using timestamp + uniqid.
     */
    public function makeUnique(string $fileName): string
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $nameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);

        return $nameWithoutExt . '_' . date('ymdhis') . uniqid() . '.' . $extension;
    }

    /**
     * Transliterate a string to ASCII.
     */
    protected function transliterate(string $text): string
    {
        if (class_exists(\MicroweberPackages\Helper\URLify::class)) {
            return \MicroweberPackages\Helper\URLify::filter($text);
        }

        // Basic transliteration fallback
        if (function_exists('transliterator_transliterate')) {
            $result = transliterator_transliterate('Any-Latin; Latin-ASCII', $text);
            return $result ?: $text;
        }

        return $text;
    }
}