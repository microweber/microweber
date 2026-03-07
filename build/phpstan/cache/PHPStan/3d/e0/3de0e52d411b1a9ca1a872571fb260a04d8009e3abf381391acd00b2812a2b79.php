<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/File.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\File
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-88ac7ba4edfb018e9f7d3b669678429b3ba40d9a11b60e6d8bbe202d3a819efd-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\File',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/File.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\File',
    'shortName' => 'File',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * This object represents files hosted on Stripe\'s servers. You can upload
 * files with the <a href="https://stripe.com/docs/api#create_file">create file</a> request
 * (for example, when uploading dispute evidence). Stripe also
 * creates files independently (for example, the results of a <a href="#scheduled_queries">Sigma scheduled
 * query</a>).
 *
 * Related guide: <a href="https://stripe.com/docs/file-upload">File upload guide</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|int $expires_at The file expires and isn\'t available at this time in epoch seconds.
 * @property null|string $filename The suitable name for saving the file to a filesystem.
 * @property null|Collection<FileLink> $links A list of <a href="https://stripe.com/docs/api#file_links">file links</a> that point at this file.
 * @property string $purpose The <a href="https://stripe.com/docs/file-upload#uploading-a-file">purpose</a> of the uploaded file.
 * @property int $size The size of the file object in bytes.
 * @property null|string $title A suitable title for the document.
 * @property null|string $type The returned file type (for example, <code>csv</code>, <code>pdf</code>, <code>jpg</code>, or <code>png</code>).
 * @property null|string $url Use your live secret API key to download the file from this URL.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 121,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Stripe\\ApiOperations\\Create',
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'file\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 27,
            'startFilePos' => 1688,
            'endTokenPos' => 27,
            'endFilePos' => 1693,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'PURPOSE_ACCOUNT_REQUIREMENT' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_ACCOUNT_REQUIREMENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'account_requirement\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 36,
            'startFilePos' => 1737,
            'endTokenPos' => 36,
            'endFilePos' => 1757,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 62,
      ),
      'PURPOSE_ADDITIONAL_VERIFICATION' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_ADDITIONAL_VERIFICATION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'additional_verification\'',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 45,
            'startFilePos' => 1804,
            'endTokenPos' => 45,
            'endFilePos' => 1828,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 70,
      ),
      'PURPOSE_BUSINESS_ICON' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_BUSINESS_ICON',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'business_icon\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 54,
            'startFilePos' => 1865,
            'endTokenPos' => 54,
            'endFilePos' => 1879,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'PURPOSE_BUSINESS_LOGO' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_BUSINESS_LOGO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'business_logo\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 63,
            'startFilePos' => 1916,
            'endTokenPos' => 63,
            'endFilePos' => 1930,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'PURPOSE_CUSTOMER_SIGNATURE' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_CUSTOMER_SIGNATURE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'customer_signature\'',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 72,
            'startFilePos' => 1972,
            'endTokenPos' => 72,
            'endFilePos' => 1991,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 60,
      ),
      'PURPOSE_DISPUTE_EVIDENCE' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_DISPUTE_EVIDENCE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'dispute_evidence\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 81,
            'startFilePos' => 2031,
            'endTokenPos' => 81,
            'endFilePos' => 2048,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 56,
      ),
      'PURPOSE_DOCUMENT_PROVIDER_IDENTITY_DOCUMENT' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_DOCUMENT_PROVIDER_IDENTITY_DOCUMENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'document_provider_identity_document\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 90,
            'startFilePos' => 2107,
            'endTokenPos' => 90,
            'endFilePos' => 2143,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 94,
      ),
      'PURPOSE_FINANCE_REPORT_RUN' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_FINANCE_REPORT_RUN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'finance_report_run\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 99,
            'startFilePos' => 2185,
            'endTokenPos' => 99,
            'endFilePos' => 2204,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 60,
      ),
      'PURPOSE_FINANCIAL_ACCOUNT_STATEMENT' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_FINANCIAL_ACCOUNT_STATEMENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'financial_account_statement\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 108,
            'startFilePos' => 2255,
            'endTokenPos' => 108,
            'endFilePos' => 2283,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 78,
      ),
      'PURPOSE_IDENTITY_DOCUMENT' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_IDENTITY_DOCUMENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'identity_document\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 117,
            'startFilePos' => 2324,
            'endTokenPos' => 117,
            'endFilePos' => 2342,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 58,
      ),
      'PURPOSE_IDENTITY_DOCUMENT_DOWNLOADABLE' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_IDENTITY_DOCUMENT_DOWNLOADABLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'identity_document_downloadable\'',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 126,
            'startFilePos' => 2396,
            'endTokenPos' => 126,
            'endFilePos' => 2427,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 84,
      ),
      'PURPOSE_ISSUING_REGULATORY_REPORTING' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_ISSUING_REGULATORY_REPORTING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'issuing_regulatory_reporting\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 135,
            'startFilePos' => 2479,
            'endTokenPos' => 135,
            'endFilePos' => 2508,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 80,
      ),
      'PURPOSE_PCI_DOCUMENT' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_PCI_DOCUMENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pci_document\'',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 144,
            'startFilePos' => 2544,
            'endTokenPos' => 144,
            'endFilePos' => 2557,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'PURPOSE_SELFIE' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_SELFIE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'selfie\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 153,
            'startFilePos' => 2587,
            'endTokenPos' => 153,
            'endFilePos' => 2594,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'PURPOSE_SIGMA_SCHEDULED_QUERY' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_SIGMA_SCHEDULED_QUERY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sigma_scheduled_query\'',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 162,
            'startFilePos' => 2639,
            'endTokenPos' => 162,
            'endFilePos' => 2661,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 66,
      ),
      'PURPOSE_TAX_DOCUMENT_USER_UPLOAD' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_TAX_DOCUMENT_USER_UPLOAD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tax_document_user_upload\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 171,
            'startFilePos' => 2709,
            'endTokenPos' => 171,
            'endFilePos' => 2734,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 72,
      ),
      'PURPOSE_TERMINAL_ANDROID_APK' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_TERMINAL_ANDROID_APK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'terminal_android_apk\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 180,
            'startFilePos' => 2778,
            'endTokenPos' => 180,
            'endFilePos' => 2799,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 64,
      ),
      'PURPOSE_TERMINAL_READER_SPLASHSCREEN' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'PURPOSE_TERMINAL_READER_SPLASHSCREEN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'terminal_reader_splashscreen\'',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 189,
            'startFilePos' => 2851,
            'endTokenPos' => 189,
            'endFilePos' => 2880,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 80,
      ),
      'OBJECT_NAME_ALT' => 
      array (
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'name' => 'OBJECT_NAME_ALT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'file_upload\'',
          'attributes' => 
          array (
            'startLine' => 95,
            'endLine' => 95,
            'startTokenPos' => 329,
            'startFilePos' => 4633,
            'endTokenPos' => 329,
            'endFilePos' => 4645,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'all' => 
      array (
        'name' => 'all',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 206,
                'startFilePos' => 3459,
                'endTokenPos' => 206,
                'endFilePos' => 3462,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 32,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 213,
                'startFilePos' => 3473,
                'endTokenPos' => 213,
                'endFilePos' => 3476,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 48,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a list of the files that your account has access to. Stripe sorts and
 * returns the files by their creation dates, placing the most recently created
 * files at the top.
 *
 * @param null|array{created?: array|int, ending_before?: string, expand?: string[], limit?: int, purpose?: string, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<File> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 63,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'currentClassName' => 'Stripe\\File',
        'aliasName' => NULL,
      ),
      'retrieve' => 
      array (
        'name' => 'retrieve',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 37,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 82,
                'endLine' => 82,
                'startTokenPos' => 269,
                'startFilePos' => 4170,
                'endTokenPos' => 269,
                'endFilePos' => 4173,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 42,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieves the details of an existing file object. After you supply a unique file
 * ID, Stripe returns the corresponding file object. Learn how to <a
 * href="/docs/file-upload#download-file-contents">access file contents</a>.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return File
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 82,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'currentClassName' => 'Stripe\\File',
        'aliasName' => NULL,
      ),
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 109,
                'endLine' => 109,
                'startTokenPos' => 363,
                'startFilePos' => 4970,
                'endTokenPos' => 363,
                'endFilePos' => 4973,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 109,
                'endLine' => 109,
                'startTokenPos' => 370,
                'startFilePos' => 4984,
                'endTokenPos' => 370,
                'endFilePos' => 4987,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 51,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return File the created file
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 109,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\File',
        'implementingClassName' => 'Stripe\\File',
        'currentClassName' => 'Stripe\\File',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
        'Stripe\\ApiOperations\\Create' => 
        array (
          0 => 
          array (
            'alias' => '_create',
            'method' => 'create',
            'hash' => 'stripe\\apioperations\\create::create',
          ),
        ),
      ),
      'modifiers' => 
      array (
        'stripe\\apioperations\\create::create' => 2,
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
        'stripe\\apioperations\\create::create' => 'Stripe\\ApiOperations\\Create::create',
      ),
    ),
  ),
));