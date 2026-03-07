<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../finlet/flexmail/src/FlexmailAPI/FlexmailAPI.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Finlet\flexmail\FlexmailAPI\FlexmailAPI
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-00490956addbd7c9fccc4a49c498d1dfacfeed67368fb389067b9ff353c299e8-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../finlet/flexmail/src/FlexmailAPI/FlexmailAPI.php',
      ),
    ),
    'namespace' => 'Finlet\\flexmail\\FlexmailAPI',
    'name' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
    'shortName' => 'FlexmailAPI',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 147,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPIInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'soapClient' => 
      array (
        'declaringClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'implementingClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'name' => 'soapClient',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'NULL',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 35,
            'startFilePos' => 280,
            'endTokenPos' => 35,
            'endFilePos' => 283,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 3,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'config' => 
      array (
        'declaringClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'implementingClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'name' => 'config',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'NULL',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 44,
            'startFilePos' => 309,
            'endTokenPos' => 44,
            'endFilePos' => 312,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 3,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Finlet\\flexmail\\Config\\ConfigInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 20,
            'endLine' => 20,
            'startColumn' => 31,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 *
 */',
        'startLine' => 20,
        'endLine' => 22,
        'startColumn' => 3,
        'endColumn' => 3,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Finlet\\flexmail\\FlexmailAPI',
        'declaringClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'implementingClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'currentClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'aliasName' => NULL,
      ),
      'service' => 
      array (
        'name' => 'service',
        'parameters' => 
        array (
          'service' => 
          array (
            'name' => 'service',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 27,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the request Service Instance
 *
 * @param String $service Requested service name
 *
 * @return Object An instance of the requested service
 */',
        'startLine' => 31,
        'endLine' => 35,
        'startColumn' => 3,
        'endColumn' => 3,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Finlet\\flexmail\\FlexmailAPI',
        'declaringClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'implementingClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'currentClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'aliasName' => NULL,
      ),
      'stripHeader' => 
      array (
        'name' => 'stripHeader',
        'parameters' => 
        array (
          'response' => 
          array (
            'name' => 'response',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 38,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'debug_mode' => 
          array (
            'name' => 'debug_mode',
            'default' => 
            array (
              'code' => 'FALSE',
              'attributes' => 
              array (
                'startLine' => 44,
                'endLine' => 44,
                'startTokenPos' => 129,
                'startFilePos' => 1048,
                'endTokenPos' => 129,
                'endFilePos' => 1052,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 49,
            'endColumn' => 67,
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
 * Reove header/error codes from the response
 *
 * @param stdClass $response The response from the API
 *
 * @return stdClass The same stdClass without the header information
 */',
        'startLine' => 44,
        'endLine' => 56,
        'startColumn' => 3,
        'endColumn' => 3,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Finlet\\flexmail\\FlexmailAPI',
        'declaringClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'implementingClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'currentClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'aliasName' => NULL,
      ),
      'parseArray' => 
      array (
        'name' => 'parseArray',
        'parameters' => 
        array (
          'arr' => 
          array (
            'name' => 'arr',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 33,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 226,
                'startFilePos' => 1669,
                'endTokenPos' => 226,
                'endFilePos' => 1672,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'stdClass',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 45,
            'endColumn' => 68,
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
 * Convert two-(or-more)-dimensional arrays to an stdClass object
 *
 * @param array $arr The array to convert
 * @param stdClass $parent The object to convert it to
 *
 * @return stdClass The converted array
 */',
        'startLine' => 66,
        'endLine' => 80,
        'startColumn' => 3,
        'endColumn' => 3,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Finlet\\flexmail\\FlexmailAPI',
        'declaringClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'implementingClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'currentClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'aliasName' => NULL,
      ),
      'execute' => 
      array (
        'name' => 'execute',
        'parameters' => 
        array (
          'service' => 
          array (
            'name' => 'service',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 30,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 40,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Execute the requested call
 *
 * @param string $service The name of the service to execute
 * @param array $parameters All parameter in an assiociative array
 *
 * @return type
 *
 * @throws Exception
 */',
        'startLine' => 92,
        'endLine' => 114,
        'startColumn' => 3,
        'endColumn' => 3,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Finlet\\flexmail\\FlexmailAPI',
        'declaringClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'implementingClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'currentClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'aliasName' => NULL,
      ),
      'createSoapClient' => 
      array (
        'name' => 'createSoapClient',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new SOAP Client
 *
 * @returns void
 */',
        'startLine' => 121,
        'endLine' => 131,
        'startColumn' => 3,
        'endColumn' => 3,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Finlet\\flexmail\\FlexmailAPI',
        'declaringClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'implementingClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'currentClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'aliasName' => NULL,
      ),
      'getRequestHeader' => 
      array (
        'name' => 'getRequestHeader',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Function to create the user\'s personal request header
 *
 * @return stdClass The user\'s personal header
 */',
        'startLine' => 138,
        'endLine' => 146,
        'startColumn' => 3,
        'endColumn' => 3,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Finlet\\flexmail\\FlexmailAPI',
        'declaringClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'implementingClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'currentClassName' => 'Finlet\\flexmail\\FlexmailAPI\\FlexmailAPI',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));