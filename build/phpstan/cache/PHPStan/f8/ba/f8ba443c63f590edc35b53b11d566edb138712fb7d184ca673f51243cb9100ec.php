<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../ramsey/uuid/src/DeprecatedUuidInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Ramsey\Uuid\DeprecatedUuidInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-908dd51e732ac52830a17d96c3379195c96be598688db829f729b6082727b36b-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../ramsey/uuid/src/DeprecatedUuidInterface.php',
      ),
    ),
    'namespace' => 'Ramsey\\Uuid',
    'name' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
    'shortName' => 'DeprecatedUuidInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * This interface encapsulates deprecated methods for ramsey/uuid
 *
 * @immutable
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 126,
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
    ),
    'immediateMethods' => 
    array (
      'getNumberConverter' => 
      array (
        'name' => 'getNumberConverter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Ramsey\\Uuid\\Converter\\NumberConverterInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated This method will be removed in 5.0.0. There is no alternative recommendation, so plan accordingly.
 */',
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 67,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getFieldsHex' => 
      array (
        'name' => 'getFieldsHex',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance.
 *
 * @return string[]
 */',
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getClockSeqHiAndReservedHex' => 
      array (
        'name' => 'getClockSeqHiAndReservedHex',
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
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getClockSeqHiAndReserved()}.
 */',
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getClockSeqLowHex' => 
      array (
        'name' => 'getClockSeqLowHex',
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
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getClockSeqLow()}.
 */',
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 48,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getClockSequenceHex' => 
      array (
        'name' => 'getClockSequenceHex',
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
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getClockSeq()}.
 */',
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 50,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getDateTime' => 
      array (
        'name' => 'getDateTime',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'DateTimeInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated In ramsey/uuid version 5.0.0, this will be removed from the interface. It is available at
 *     {@see UuidV1::getDateTime()}.
 */',
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getLeastSignificantBitsHex' => 
      array (
        'name' => 'getLeastSignificantBitsHex',
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
 * @deprecated This method will be removed in 5.0.0. There is no direct alternative, but the same information may be
 *     obtained by splitting in half the value returned by {@see UuidInterface::getHex()}.
 */',
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getMostSignificantBitsHex' => 
      array (
        'name' => 'getMostSignificantBitsHex',
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
 * @deprecated This method will be removed in 5.0.0. There is no direct alternative, but the same information may be
 *     obtained by splitting in half the value returned by {@see UuidInterface::getHex()}.
 */',
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 56,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getNodeHex' => 
      array (
        'name' => 'getNodeHex',
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
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getNode()}.
 */',
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getTimeHiAndVersionHex' => 
      array (
        'name' => 'getTimeHiAndVersionHex',
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
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getTimeHiAndVersion()}.
 */',
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getTimeLowHex' => 
      array (
        'name' => 'getTimeLowHex',
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
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getTimeLow()}.
 */',
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getTimeMidHex' => 
      array (
        'name' => 'getTimeMidHex',
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
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getTimeMid()}.
 */',
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getTimestampHex' => 
      array (
        'name' => 'getTimestampHex',
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
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getTimestamp()}.
 */',
        'startLine' => 111,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getVariant' => 
      array (
        'name' => 'getVariant',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'int',
                  'isIdentifier' => true,
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getVariant()}.
 */',
        'startLine' => 118,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'aliasName' => NULL,
      ),
      'getVersion' => 
      array (
        'name' => 'getVersion',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'int',
                  'isIdentifier' => true,
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated Use {@see UuidInterface::getFields()} to get a {@see FieldsInterface} instance. If it is a
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface} instance, you may call
 *     {@see \\Ramsey\\Uuid\\Rfc4122\\FieldsInterface::getVersion()}.
 */',
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
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