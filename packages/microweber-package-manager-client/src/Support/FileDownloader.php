<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Support;

/**
 * Streaming HTTP file downloader (chunked via cURL).
 */
trait FileDownloader
{
    /**
     * Download a remote file to a local path.
     *
     * @param resource|string $dest File path or open stream resource
     * @return true|string True on success, or cURL error string on failure
     */
    public function downloadBigFile(string $url, mixed $dest, string|false $logFile = false): true|string
    {
        $closeDest = false;
        if (is_string($dest)) {
            $dir = dirname($dest);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $handle = fopen($dest, 'w');
            if ($handle === false) {
                return 'Unable to open destination for writing: ' . $dest;
            }
            $closeDest = true;
        } elseif (is_resource($dest)) {
            $handle = $dest;
        } else {
            return 'Invalid destination for download';
        }

        if ($url === '') {
            if ($closeDest) {
                fclose($handle);
            }

            return 'Empty download URL';
        }

        $userAgent = $this->httpUserAgent();
        if ($userAgent === '') {
            $userAgent = 'MicroweberPackageManagerClient/1.0';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FILE, $handle);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->httpTimeout());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->httpConnectTimeout());
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->httpVerifySsl());

        if ($logFile !== false && $logFile !== '') {
            $logHandle = @fopen($logFile, 'a+');
            if (is_resource($logHandle)) {
                curl_setopt($ch, CURLOPT_STDERR, $logHandle);
                curl_setopt($ch, CURLOPT_WRITEHEADER, $logHandle);
                curl_setopt($ch, CURLOPT_VERBOSE, true);
            }
        }

        $headers = $this->prepareHeaders();
        if ($headers !== []) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $return = curl_exec($ch);

        if ($return === false) {
            $error = curl_error($ch);
            curl_close($ch);
            if ($closeDest) {
                fclose($handle);
            }

            return $error !== '' ? $error : 'Unknown cURL download error';
        }

        curl_close($ch);
        if ($closeDest) {
            fclose($handle);
        }

        return true;
    }

    /**
     * @return list<string>
     */
    abstract public function prepareHeaders(): array;

    protected function httpTimeout(): int
    {
        return 30;
    }

    protected function httpConnectTimeout(): int
    {
        return 10;
    }

    protected function httpUserAgent(): string
    {
        return 'MicroweberPackageManagerClient/1.0';
    }

    protected function httpVerifySsl(): bool
    {
        return true;
    }
}
