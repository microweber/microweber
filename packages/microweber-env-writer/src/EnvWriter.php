<?php

namespace MicroweberPackages\EnvWriter;

class EnvWriter
{
    /**
     * Save key-value pairs to a .env file.
     *
     * Updates existing keys in place, appends new keys at the end,
     * removes duplicate keys, collapses consecutive blank lines,
     * and properly quotes values that contain special characters.
     *
     * @param array<string, mixed> $values Key-value pairs to write.
     * @param string $envFilePath Absolute path to the .env file.
     * @return bool True on success, false on failure.
     */
    public function save(array $values, string $envFilePath): bool
    {
        if (!file_exists($envFilePath)) {
            // If file doesn't exist, create it with the given values
            $lines = [];
            foreach ($values as $key => $value) {
                $lines[] = $key . '=' . $this->formatValue($value);
            }
            return file_put_contents($envFilePath, implode("\n", $lines) . "\n") !== false;
        }

        $content = file_get_contents($envFilePath);
        if ($content === false) {
            return false;
        }

        $lines = preg_split('/\r\n|\r|\n/', $content);
        $newLines = [];
        $processedKeys = [];
        $lastLineEmpty = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Handle empty lines - collapse consecutive blanks
            if ($trimmed === '') {
                if (!$lastLineEmpty) {
                    $newLines[] = '';
                    $lastLineEmpty = true;
                }
                continue;
            }

            // Handle comments - preserve them
            if (str_starts_with($trimmed, '#')) {
                $newLines[] = $line;
                $lastLineEmpty = false;
                continue;
            }

            // Process key=value lines
            if (str_contains($trimmed, '=')) {
                [$key] = explode('=', $trimmed, 2);
                $key = trim($key);

                // Skip duplicate keys (keep first occurrence only)
                if (isset($processedKeys[$key])) {
                    continue;
                }

                $processedKeys[$key] = true;

                // If this key is one we're updating, write the new value
                if (array_key_exists($key, $values)) {
                    $newLines[] = $key . '=' . $this->formatValue($values[$key]);
                } else {
                    // Keep existing line as-is
                    $newLines[] = $line;
                }
                $lastLineEmpty = false;
            } else {
                // Keep other non-key=value lines (e.g. malformed lines)
                $newLines[] = $line;
                $lastLineEmpty = false;
            }
        }

        // Append any new keys that weren't in the original file
        foreach ($values as $key => $value) {
            if (!isset($processedKeys[$key])) {
                $newLines[] = $key . '=' . $this->formatValue($value);
                $processedKeys[$key] = true;
                $lastLineEmpty = false;
            }
        }

        // Trim trailing empty lines
        while (!empty($newLines) && trim(end($newLines)) === '') {
            array_pop($newLines);
        }

        $newContent = implode("\n", $newLines) . "\n";

        // Only write if content has changed
        if ($content === $newContent) {
            return true;
        }

        return file_put_contents($envFilePath, $newContent) !== false;
    }

    /**
     * Format a value for writing to .env file.
     *
     * Handles booleans, nulls, numbers, and strings.
     * Quotes strings that contain spaces, quotes, or hash characters.
     *
     * @param mixed $value
     * @return string
     */
    public function formatValue(mixed $value): string
    {
        // Handle booleans
        if ($value === true) {
            return 'true';
        }
        if ($value === false) {
            return 'false';
        }

        // Handle null
        if ($value === null) {
            return '';
        }

        // Cast to string for processing
        $value = (string) $value;

        // Empty string
        if ($value === '') {
            return '';
        }

        // Check if quoting is needed
        if (str_contains($value, ' ')
            || str_contains($value, '#')
            || str_contains($value, '"')
            || str_contains($value, "'")
            || str_contains($value, '$')
            || str_contains($value, '\\')
            || str_contains($value, "\t")
        ) {
            return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
        }

        return $value;
    }

    /**
     * Read and parse a .env file into an associative array.
     *
     * @param string $envFilePath
     * @return array<string, string>
     */
    public function read(string $envFilePath): array
    {
        if (!file_exists($envFilePath)) {
            return [];
        }

        $content = file_get_contents($envFilePath);
        if ($content === false) {
            return [];
        }

        $result = [];
        $lines = preg_split('/\r\n|\r|\n/', $content);

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }

            if (str_contains($trimmed, '=')) {
                [$key, $val] = explode('=', $trimmed, 2);
                $key = trim($key);
                $val = trim($val);

                // Remove surrounding quotes
                if (strlen($val) >= 2) {
                    if ((str_starts_with($val, '"') && str_ends_with($val, '"'))
                        || (str_starts_with($val, "'") && str_ends_with($val, "'"))) {
                        $val = substr($val, 1, -1);
                    }
                }

                // Only keep first occurrence
                if (!isset($result[$key])) {
                    $result[$key] = $val;
                }
            }
        }

        return $result;
    }
}