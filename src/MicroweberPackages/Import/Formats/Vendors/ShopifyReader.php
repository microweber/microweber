<?php
namespace MicroweberPackages\Import\Formats\Vendors;

use Microweber\Providers\UrlManager;

class ShopifyReader extends DefaultXmlReader
{
    public function read($file)
    {
        $doc = new \DOMDocument;
        $doc->loadXML(file_get_contents($file));

        $data = [];
        foreach ($doc->getElementsByTagName('entry') as $entry) {

            $nodeData = $this->getArrayFromDomNode($entry);

            $saveData = [];

            if (isset($nodeData['title'][0]['#text'])) {
                $saveData['title'] = $nodeData['title'][0]['#text'];
            }

            $variants = [];
            if (isset($nodeData['s:variant'])) {
                foreach ($nodeData['s:variant'] as $variant) {

                    $saveVariant = [];

                    if (isset($variant['title'][0]['#text'])) {
                        $saveVariant['title'] = $variant['title'][0]['#text'];
                    }

                    if (isset($variant['s:price'][0]['#text'])) {
                        $saveVariant['price'] = $variant['s:price'][0]['#text'];
                    }

                    if (isset($variant['s:sku'][0]['#text'])) {
                        $saveVariant['sku'] = $variant['s:sku'][0]['#text'];
                    }

                    if (isset($variant['s:vendor'][0]['#text'])) {
                        $saveVariant['content_data']['brand'] = $variant['s:vendor'][0]['#text'];
                    }

                    if (isset($variant['s:grams'][0]['#text'])) {
                        $saveVariant['content_data']['grams'] = $variant['s:grams'][0]['#text'];
                    }

                    if (isset($variant['s:price'][0]['currency'])) {
                        $saveVariant['content_data']['currency'] = $variant['s:price'][0]['currency'];
                    }

                    $contentBody = false;
                    if (isset($nodeData['summary'][0]['#text'][0])) {
                        $contentBody = $nodeData['summary'][0]['#text'][0];
                    }

                    $saveVariant['content_body'] = $contentBody;

                     $firstImage = app()->media_manager->get_first_image_from_html($contentBody);
                    if ($firstImage) {
                        $saveVariant['pictures'] = $firstImage;
                    }


                    $variants[] = $saveVariant;
                }
            }

            if (!empty($variants)) {
                $saveData['price'] = $variants[0]['price'];
                $saveData['content_body'] = $variants[0]['content_body'];
                $saveData['pictures'] = $variants[0]['pictures'];
            }

            $saveData['variants'] = $variants;

            $data[] = $saveData;
        }

        return array("content"=>$data);;

    }
}
