<?php

namespace Sabre\XML;

use
    Exception,
    LibXMLError;

/**
 * This exception is thrown when the Readers runs into a parsing error.
 *
 * This exception effectively wraps 1 or more LibXMLError objects.
 *
 * @copyright Copyright (C) 2007-2014 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class LibXMLException extends Exception {

    /**
     * The error list.
     *
     * @var LibXMLError[]
     */
    protected $errors;

    /**
     * Creates the exception.
     *
     * You should pass a list of LibXMLError objects in its constructor.
     *
     * @param LibXMLError[] $errors
     * @param int $code
     * @param Exception $previousException
     */
    public function __construct(array $errors, $code = null, Exception $previousException = null) {

        $this->errors = $errors;
        parent::__construct($errors[0]->message . ' on line ' . $errors[0]->line . ', column ' . $errors[0]->column, $code, $previousException);

    }

    /**
     * Returns the LibXML errors
     *
     * @return void
     */
    public function getErrors() {

        return $this->errors;

    }

}
