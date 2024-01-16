<?php
namespace MicroweberPackages\Template\Adapters;

class GoogleFontDownloader {

    public $outputPath = '';
    public $fontUrls = [];

    public function setOutputPath($path) {
        $this->outputPath = $path;
    }

    public function addFontUrl($url) {
        $this->fontUrls[] = $url;
    }


    public function download()
    {
        if (empty($this->fontUrls)) {
            return [
                'error' => 'No font urls to download'
            ];
        }

        foreach ($this->fontUrls as $fontUrl) {

            $getContent = $this->_downloadFile($fontUrl);

            $parser = new \Sabberworm\CSS\Parser($getContent);
            $cssDocument = $parser->parse();
            if (empty($cssDocument)) {
                continue;
            }
            $cssContents = $cssDocument->getContents();
            if (empty($cssContents)) {
                continue;
            }

            $fontUrls = [];
            $fontFamily = '';
            foreach ($cssContents as $cssContent) {
                $cssRules = $cssContent->getRules();
                if (empty($cssRules)) {
                    continue;
                }
                foreach ($cssRules as $cssRule) {
                    if ($cssRule->getRule() == 'font-family') {
                        $fontFamily = $cssRule->getValue()->getString();
                    }
                    if ($cssRule->getRule() == 'src') {
                        $cssRuleValue = $cssRule->getValue();
                        $fontFileComponents = $cssRuleValue->getListComponents();
                        if (!isset($fontFileComponents[0]) or !method_exists($fontFileComponents[0], 'getURL')) {
                            continue;
                        }
                        $fontUrl =  $fontFileComponents[0]->getURL()->getString();
                        $fontUrls[md5($fontUrl)] = $fontUrl;
                    }
                }
            }

            if (empty($fontUrls)) {
                continue;
            }

            $newFontFileContent = $getContent;
            $fontPath = $this->outputPath .DS. str_slug($fontFamily);
            if (!is_dir($fontPath)) {
                mkdir_recursive($fontPath);
            }

            foreach ($fontUrls as $fontFileUrl) {
                $fontFileContent = $this->_downloadFile($fontFileUrl);
                $fontFilename = basename($fontFileUrl);
                file_put_contents($fontPath .DS. $fontFilename, $fontFileContent);
                $newFontFileContent = str_replace($fontFileUrl, './'.$fontFilename, $newFontFileContent);
            }

            file_put_contents($fontPath . DS . 'font.css', $newFontFileContent);

        }
    }

    protected function _downloadFile($url) {

        // Set User Agent
        $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:121.0) Gecko/20100101 Firefox/121.0';
        $headers = [
            'User-Agent' => $userAgent,
        ];

        $options = [
            'headers' => $headers,
            'timeout' => 30,
            'verify' => false,
        ];

        $client = new \GuzzleHttp\Client($options);

        $response = $client->request('GET', $url);
        $getContent = $response->getBody()->getContents();

        return $getContent;

    }

}
