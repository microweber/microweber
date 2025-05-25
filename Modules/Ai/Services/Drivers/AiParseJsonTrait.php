<?php

namespace Modules\Ai\Services\Drivers;

use \JsonMachine\Items;
use NeuronAI\StructuredOutput\JsonExtractor;

trait AiParseJsonTrait
{

    public function parseJson($content)
    {
        if (is_string($content)) {
            $content = mb_trim($content);
            // Attempt to decode JSON directly
            $contentTryDecode = @json_decode($content, true);
            if ($contentTryDecode) {
                return $contentTryDecode;
            }




            try {
                //try to parse wtih JsonExtractor
                $extractor = new JsonExtractor();
                $contentTryDecode= $extractor->getJson($content);
                if(is_string($contentTryDecode)) {
                    $contentTryDecode = @json_decode($contentTryDecode, true);
                }
                if ($contentTryDecode) {
                    return $contentTryDecode;
                }
            } catch (\Exception $e) {
                // If JsonExtractor fails, we will try to parse it as a string
                // This is useful for cases where the content is not valid JSON

             }


            if (str_contains($content, '```json')) {
                $content = str_replace('```json', '', $content);
                $content = str_replace('```', '', $content);
                $content = mb_trim($content);



                try {
                    $contentParse = Items::fromString($content);

                    $contentParse = iterator_to_array($contentParse);
                    if(!empty($contentParse)) {
                        $content = $contentParse;
                    }
                } catch (\Exception $e) {

                }



            } else {


                $content = json_decode($content, true);
            }
        }
        return $content;

    }

}
