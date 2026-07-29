<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Downloaders;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Str;
use Sabberworm\CSS\CSSList\CSSList;
use Sabberworm\CSS\Parser;
use Sabberworm\CSS\Property\Import;
use Sabberworm\CSS\RuleSet\RuleSet;
use Sabberworm\CSS\Value\CSSString;
use Sabberworm\CSS\Value\RuleValueList;
use Sabberworm\CSS\Value\URL;

/**
 * Downloads a Google Fonts CSS document and its binary font files into a local folder.
 *
 * Standalone: no CMS helpers. Injects optional HTTP client (Guzzle or Microweber HttpClientFactory).
 */
class GoogleFontDownloader
{
    public string $outputPath = '';

    /** @var list<string> */
    public array $fontUrls = [];

    private ClientInterface $httpClient;

    public function __construct(?ClientInterface $httpClient = null)
    {
        if ($httpClient !== null) {
            $this->httpClient = $httpClient;

            return;
        }

        // Prefer Microweber secure client when available
        if (class_exists(\MicroweberPackages\Http\HttpClientFactory::class)) {
            $this->httpClient = \MicroweberPackages\Http\HttpClientFactory::guzzle([
                'timeout' => 30,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (compatible; MicroweberTemplateFonts/1.0)',
                ],
            ]);

            return;
        }

        $this->httpClient = new Client([
            'timeout' => 30,
            'verify' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (compatible; MicroweberTemplateFonts/1.0)',
            ],
        ]);
    }

    public function setOutputPath(string $path): self
    {
        $this->outputPath = rtrim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    public function addFontUrl(string $url): self
    {
        $this->fontUrls[] = $url;

        return $this;
    }

    /**
     * @return array{error?: string, fonts?: list<array{family: string, path: string, css: string}>}
     */
    public function download(): array
    {
        if ($this->fontUrls === []) {
            return ['error' => 'No font urls to download'];
        }

        if ($this->outputPath === '') {
            return ['error' => 'Output path is not set'];
        }

        if (!is_dir($this->outputPath) && !mkdir($this->outputPath, 0755, true) && !is_dir($this->outputPath)) {
            return ['error' => 'Unable to create output path: ' . $this->outputPath];
        }

        $downloaded = [];

        foreach ($this->fontUrls as $fontUrl) {
            try {
                $cssContent = $this->downloadFile($fontUrl);
            } catch (\Throwable $e) {
                continue;
            }

            if ($cssContent === '') {
                continue;
            }

            $result = $this->processCssDocument($cssContent);
            if ($result === null) {
                continue;
            }

            $downloaded[] = $result;
        }

        return ['fonts' => $downloaded];
    }

    /**
     * @return array{family: string, path: string, css: string}|null
     */
    protected function processCssDocument(string $cssContent): ?array
    {
        $parser = new Parser($cssContent);
        $cssDocument = $parser->parse();
        $contents = $cssDocument->getContents();
        if ($contents === []) {
            return null;
        }

        $fontUrls = [];
        $fontFamily = '';

        $this->extractFontData($contents, $fontUrls, $fontFamily);

        if ($fontUrls === [] || $fontFamily === '') {
            return null;
        }

        $fontSlug = Str::slug($fontFamily);
        $fontPath = $this->outputPath . DIRECTORY_SEPARATOR . $fontSlug;
        if (!is_dir($fontPath) && !mkdir($fontPath, 0755, true) && !is_dir($fontPath)) {
            return null;
        }

        $newFontFileContent = $cssContent;
        foreach ($fontUrls as $fontFileUrl) {
            try {
                $fontFileContent = $this->downloadFile($fontFileUrl);
            } catch (\Throwable) {
                continue;
            }
            $fontFilename = basename(parse_url($fontFileUrl, PHP_URL_PATH) ?: $fontFileUrl);
            if ($fontFilename === '' || $fontFilename === '/' || $fontFilename === '.') {
                $fontFilename = md5($fontFileUrl) . '.woff2';
            }
            file_put_contents($fontPath . DIRECTORY_SEPARATOR . $fontFilename, $fontFileContent);
            $newFontFileContent = str_replace($fontFileUrl, './' . $fontFilename, $newFontFileContent);
        }

        $cssFile = $fontPath . DIRECTORY_SEPARATOR . 'font.css';
        file_put_contents($cssFile, $newFontFileContent);

        return [
            'family' => $fontFamily,
            'path' => $fontPath,
            'css' => $cssFile,
        ];
    }

    /**
     * @param  array<int, mixed>  $contents
     * @param  array<string, string>  $fontUrls
     */
    protected function extractFontData(array $contents, array &$fontUrls, string &$fontFamily): void
    {
        foreach ($contents as $cssContent) {
            if ($cssContent instanceof CSSList) {
                $this->extractFontData($cssContent->getContents(), $fontUrls, $fontFamily);
                continue;
            }

            // Google Fonts CSS wraps every face in an @font-face block, which
            // Sabberworm parses as an AtRuleSet (NOT a DeclarationBlock). Both
            // extend RuleSet and expose getRules(), so match the parent type —
            // checking for DeclarationBlock alone skips every @font-face and
            // yields zero downloaded fonts.
            if (!$cssContent instanceof RuleSet) {
                continue;
            }

            $rules = $cssContent->getRules();
            if ($rules === []) {
                continue;
            }

            foreach ($rules as $cssRule) {
                $ruleName = $cssRule->getRule();
                $value = $cssRule->getValue();

                if ($ruleName === 'font-family') {
                    $fontFamily = $this->valueToString($value);
                }

                if ($ruleName === 'src') {
                    $this->collectSrcUrls($value, $fontUrls);
                }
            }
        }
    }

    protected function valueToString(mixed $value): string
    {
        if ($value instanceof CSSString) {
            return $value->getString();
        }
        if ($value instanceof URL) {
            $inner = $value->getURL();

            return $this->valueToString($inner);
        }
        if (is_string($value)) {
            return trim($value, " \t\n\r\0\x0B\"'");
        }
        if (is_object($value) && method_exists($value, '__toString')) {
            return trim((string) $value, " \t\n\r\0\x0B\"'");
        }

        return '';
    }

    /**
     * @param  array<string, string>  $fontUrls
     */
    protected function collectSrcUrls(mixed $value, array &$fontUrls): void
    {
        if ($value instanceof URL) {
            $urlString = $this->valueToString($value->getURL());
            if ($urlString === '') {
                $urlString = $this->valueToString($value);
            }
            if ($urlString !== '') {
                $fontUrls[md5($urlString)] = $urlString;
            }

            return;
        }

        if ($value instanceof RuleValueList) {
            foreach ($value->getListComponents() as $component) {
                $this->collectSrcUrls($component, $fontUrls);
            }

            return;
        }

        if (is_object($value) && method_exists($value, 'getURL')) {
            $urlObj = $value->getURL(); // @phpstan-ignore method.notFound
            $urlString = $this->valueToString($urlObj);
            if ($urlString !== '') {
                $fontUrls[md5($urlString)] = $urlString;
            }
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function downloadFile(string $url): string
    {
        $response = $this->httpClient->request('GET', $url);

        return (string) $response->getBody()->getContents();
    }
}
