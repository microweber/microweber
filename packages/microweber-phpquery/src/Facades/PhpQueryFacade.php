<?php

namespace MicroweberPackages\PhpQuery\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \MicroweberPackages\PhpQuery\PhpQueryObject newDocument($markup = null, $contentType = null)
 * @method static \MicroweberPackages\PhpQuery\PhpQueryObject newDocumentHTML($markup = null, $charset = null)
 * @method static \MicroweberPackages\PhpQuery\PhpQueryObject newDocumentXML($markup = null, $charset = null)
 * @method static \MicroweberPackages\PhpQuery\PhpQueryObject newDocumentXHTML($markup = null, $charset = null)
 * @method static \MicroweberPackages\PhpQuery\PhpQueryObject newDocumentFile($file, $contentType = null)
 * @method static \MicroweberPackages\PhpQuery\PhpQueryObject pq($arg1, $context = null)
 * @method static void unloadDocuments($id = null)
 * @method static \MicroweberPackages\PhpQuery\PhpQueryObject getDocument($id = null)
 * @method static string|null getDocumentID($source)
 *
 * @see \MicroweberPackages\PhpQuery\PhpQueryManager
 */
class PhpQueryFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'phpquery';
    }
}