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

            $mainFontFile = md5($fontUrl) . '.css';
            if (is_file($this->outputPath .DS. $mainFontFile)) {
                $getContent = file_get_contents($this->outputPath .DS. $mainFontFile);
            } else {
                $getContent = $this->_downloadFile($fontUrl);
                file_put_contents($this->outputPath .DS. $mainFontFile, $getContent);
            }

            $parser = new \Sabberworm\CSS\Parser($getContent);
            $cssDocument = $parser->parse();
            if (empty($cssDocument)) {
                continue;
            }
            $cssContents = $cssDocument->getContents();
            if (empty($cssContents)) {
                continue;
            }

            $countSubset = 0;
            $replaceFontUrls = [];
            foreach ($cssContents as $cssContent) {
                $cssRules = $cssContent->getRules();
                if (empty($cssRules)) {
                    continue;
                }
                $countSubset++;

                $fontFamily = '';
                $fontStyle = '';
                $fontWeight = '';
                $fontFileType = '';

//                $cssComments = $cssContent->getComments();
//                if (empty($cssComments)) {
//                    continue;
//                }
//                if (!isset($cssComments[0])) {
//                    continue;
//                }
//                $fontSubset = $cssComments[0]->getComment();
//                $fontSubset = trim($fontSubset);

                foreach ($cssRules as $cssRule) {
                    if ($cssRule->getRule() == 'font-style') {
                        $fontStyle = $cssRule->getValue();
                    }
                    if ($cssRule->getRule() == 'font-weight') {
                        $fontWeight = $cssRule->getValue()->getSize();
                    }
                    if ($cssRule->getRule() == 'font-family') {
                        $fontFamily = $cssRule->getValue()->getString();
                    }
                    if ($cssRule->getRule() == 'src') {
                        $cssRuleValue = $cssRule->getValue();
                        $fontFileComponents = $cssRuleValue->getListComponents();
                        if (!isset($fontFileComponents[0]) or !method_exists($fontFileComponents[0], 'getURL')) {
                            continue;
                        }
                        $fontFileUrl = $fontFileComponents[0]->getURL()->getString();
                        $fontFileType = get_file_extension($fontFileUrl);
                    }
                }

                $fontPath = $this->outputPath .DS. str_slug($fontFamily);
                if (!is_dir($fontPath)) {
                    mkdir_recursive($fontPath);
                }
                $fontFilename = str_slug($countSubset .'-'. $fontFamily . '-' . $fontWeight . '-' . $fontStyle) . '.' . $fontFileType;
                if (is_file($fontPath .DS. $fontFilename)) {
                    continue;
                }
                $fontFileContent = $this->_downloadFile($fontFileUrl);
                file_put_contents($fontPath .DS. $fontFilename, $fontFileContent);
                $replaceFontUrls[] = [
                    'original' => $fontFileUrl,
                    'new' => './userfiles/fonts/'. str_slug($fontFamily) . DS . $fontFilename,
                ];
            }
            $newFontFileContent = $getContent;
            if (!empty($replaceFontUrls)) {
                foreach ($replaceFontUrls as $replaceFontUrl) {
                    $newFontFileContent = str_replace($replaceFontUrl['original'], $replaceFontUrl['new'], $newFontFileContent);
                }
            }

            if (!is_file($fontPath .DS. 'font.css')) {
                file_put_contents($fontPath . DS . 'font.css', $newFontFileContent);
            }
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
