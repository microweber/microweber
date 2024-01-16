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
            $getContent = app()->http->url($fontUrl)->get();

            //dd($getContent);

        }
    }


}
