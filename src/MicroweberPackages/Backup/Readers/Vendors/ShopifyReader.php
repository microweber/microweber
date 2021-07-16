<?php
namespace MicroweberPackages\Backup\Readers\Vendors;

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

            if (isset($nodeData['s:variant'])) {

                foreach ($nodeData['s:variant'] as $variant) {

                    $saveData = [];

                    if (isset($nodeData['title'][0]['#text'])) {
                        $saveData['title'] = $nodeData['title'][0]['#text'];
                    }

                    if (isset($variant['title'][0]['#text'])) {
                        $saveData['variant_title'] = $variant['title'][0]['#text'];
                    }

                    $saveData['title'] = $saveData['title'] . ' - ' . $saveData['variant_title'];

                    if (isset($variant['s:price'][0]['#text'])) {
                        $saveData['price'] = $variant['s:price'][0]['#text'];
                    }

                    if (isset($variant['s:sku'][0]['#text'])) {
                        $saveData['sku'] = $variant['s:sku'][0]['#text'];
                    }

                    if (isset($variant['s:price'][0]['currency'])) {
                        $saveData['data_currency'] = $variant['s:price'][0]['currency'];
                    }

                    $contentBody = false;
                    if (isset($nodeData['summary'][0]['#text'][0])) {
                        $contentBody = $nodeData['summary'][0]['#text'][0];
                    }

                    $saveData['content_body'] = $contentBody;

                     $firstImage = app()->media_manager->get_first_image_from_html($contentBody);
                    if ($firstImage) {
                        $saveData['pictures'] = $firstImage;
                    }


                    $data[] = $saveData;
                }
            }
        }

        dd($data);

        return array("content"=>$data);;

    }
}
