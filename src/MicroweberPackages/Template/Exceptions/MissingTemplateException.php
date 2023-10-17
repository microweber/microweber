<?php

namespace MicroweberPackages\Template\Exceptions;

class MissingTemplateException extends \Exception
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message ?: 'Template not found', $code, $previous);
    }
}
