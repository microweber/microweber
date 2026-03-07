<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Newsletter/Http/NewsletterController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Newsletter\Http\NewsletterController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-53e26c12fdea70eef21bb714987de6fa458ccd97e3dd6672c3b52eb18495115f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Newsletter\\Http\\NewsletterController',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Newsletter/Http/NewsletterController.php',
      ),
    ),
    'namespace' => 'Modules\\Newsletter\\Http',
    'name' => 'Modules\\Newsletter\\Http\\NewsletterController',
    'shortName' => 'NewsletterController',
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
    'endLine' => 84,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Routing\\Controller',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'subscribe' => 
      array (
        'name' => 'subscribe',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 31,
            'endColumn' => 46,
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
 * Subscribe a user to the newsletter.
 *
 * Expected POST parameters:
 * - name: the subscriber\'s name
 * - email: the subscriber\'s email address
 * - list_id: the newsletter list identifier
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return \\Illuminate\\Http\\JsonResponse
 */',
        'startLine' => 25,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Newsletter\\Http',
        'declaringClassName' => 'Modules\\Newsletter\\Http\\NewsletterController',
        'implementingClassName' => 'Modules\\Newsletter\\Http\\NewsletterController',
        'currentClassName' => 'Modules\\Newsletter\\Http\\NewsletterController',
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