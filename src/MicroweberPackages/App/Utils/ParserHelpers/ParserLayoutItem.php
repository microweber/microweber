<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

class ParserLayoutItem
{
    public $isProcessed = false;
    public $isProcessing = false;

    public $layout = '';

    public $parent = false;

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }

    /**
     * @param bool $isProcessed
     */
    public function setIsProcessed(bool $isProcessed): void
    {
        $this->isProcessed = $isProcessed;
    }





}
