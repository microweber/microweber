<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class GeneratorHeadTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $generator_tag = '';
        if (defined('MW_VERSION')) {
            $generator_tag = "\n" . '<meta name="generator" content="' . addslashes(mw()->ui->brand_name()) . '" />' . "\n";
         }


        return $generator_tag;
    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_HEAD;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }


    public function toArray(): array
    {
        return [
            'type' => 'generator_head_tag',
        ];
    }
}
