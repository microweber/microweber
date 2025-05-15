<?php

namespace Modules\Ai\Services\Drivers;
use \JsonMachine\Items;

trait AiParseJsonTrait
{

    public function parseJson($content)
    {
        if (is_string($content)) {
            if (str_contains($content, '```json')) {
                $content = str_replace('```json', '', $content);
                $content = str_replace('```', '', $content);
                $content = trim($content);

                $content = Items::fromString($content)	;
                $content = iterator_to_array($content);
             
             } else {
                $content = trim($content);

                $content = json_decode($content, true);
            }
        }
        return $content;

    }

}
