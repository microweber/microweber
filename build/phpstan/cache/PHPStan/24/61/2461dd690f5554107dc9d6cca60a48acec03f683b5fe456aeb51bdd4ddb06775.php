<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Concerns/Prorates.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Cashier\Concerns\Prorates
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-41324b2cd1880cc37e2e106f54263229c49df07ee258758eb96925f0eaa49744-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Concerns/Prorates.php',
      ),
    ),
    'namespace' => 'Laravel\\Cashier\\Concerns',
    'name' => 'Laravel\\Cashier\\Concerns\\Prorates',
    'shortName' => 'Prorates',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 72,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
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
      'prorationBehavior' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'name' => 'prorationBehavior',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\'create_prorations\'',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 12,
            'startTokenPos' => 23,
            'startFilePos' => 202,
            'endTokenPos' => 23,
            'endFilePos' => 220,
          ),
        ),
        'docComment' => '/**
 * Indicates if the price change should be prorated.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 62,
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
      'noProrate' => 
      array (
        'name' => 'noProrate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that the price change should not be prorated.
 *
 * @return $this
 */',
        'startLine' => 19,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'aliasName' => NULL,
      ),
      'prorate' => 
      array (
        'name' => 'prorate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that the price change should be prorated.
 *
 * @return $this
 */',
        'startLine' => 31,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'aliasName' => NULL,
      ),
      'alwaysInvoice' => 
      array (
        'name' => 'alwaysInvoice',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that the price change should always be invoiced.
 *
 * @return $this
 */',
        'startLine' => 43,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'aliasName' => NULL,
      ),
      'setProrationBehavior' => 
      array (
        'name' => 'setProrationBehavior',
        'parameters' => 
        array (
          'prorationBehavior' => 
          array (
            'name' => 'prorationBehavior',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 42,
            'endColumn' => 66,
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
 * Set the prorating behavior.
 *
 * @param  string  $prorationBehavior
 * @return $this
 */',
        'startLine' => 56,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'aliasName' => NULL,
      ),
      'prorateBehavior' => 
      array (
        'name' => 'prorateBehavior',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine the prorating behavior when updating the subscription.
 *
 * @return string
 */',
        'startLine' => 68,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\Prorates',
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