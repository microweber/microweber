<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

class ParserLayoutItem
{
    public $isProcessed = false;
    public $isProcessing = false;



    public $parent;


    public $pq = false;

    function __construct($layout = null)
    {
        if ($layout) {
            if (is_string($layout)) {
                $pq = \phpQuery::newDocument($layout);
                $this->pq = $pq;
            } else {
                $this->pq = $layout;
            }
        }
    }


    public function getPq(): \phpQueryObject
    {
        return $this->pq;
    }


    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }

    public function setIsProcessed(bool $isProcessed): void
    {
        $this->isProcessed = $isProcessed;
    }


    public function setParent(ParserLayoutItem $parent): void
    {
        $this->parent = $parent;
    }


    public function getParent(): ParserLayoutItem
    {
        return $this->parent;
    }

}
