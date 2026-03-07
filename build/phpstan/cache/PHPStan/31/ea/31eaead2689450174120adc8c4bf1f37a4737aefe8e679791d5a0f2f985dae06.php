<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../ramsey/uuid/src/UuidInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Ramsey\Uuid\UuidInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-35ee9abfe28406490ad097f874fc12b2e1cb6e1691ce6c9afaa7b640f96b2268-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Ramsey\\Uuid\\UuidInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../ramsey/uuid/src/UuidInterface.php',
      ),
    ),
    'namespace' => 'Ramsey\\Uuid',
    'name' => 'Ramsey\\Uuid\\UuidInterface',
    'shortName' => 'UuidInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A UUID is a universally unique identifier adhering to an agreed-upon representation format and standard for generation
 *
 * @immutable
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 29,
    'endLine' => 110,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Ramsey\\Uuid\\DeprecatedUuidInterface',
      1 => 'JsonSerializable',
      2 => 'Serializable',
      3 => 'Stringable',
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
      'compareTo' => 
      array (
        'name' => 'compareTo',
        'parameters' => 
        array (
          'other' => 
          array (
            'name' => 'other',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Ramsey\\Uuid\\UuidInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 31,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns -1, 0, or 1 if the UUID is less than, equal to, or greater than the other UUID
 *
 * The first of two UUIDs is greater than the second if the most significant field in which the UUIDs differ is
 * greater for the first UUID.
 *
 * @param UuidInterface $other The UUID to compare
 *
 * @return int<-1,1> -1, 0, or 1 if the UUID is less than, equal to, or greater than $other
 */',
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'aliasName' => NULL,
      ),
      'equals' => 
      array (
        'name' => 'equals',
        'parameters' => 
        array (
          'other' => 
          array (
            'name' => 'other',
            'default' => NULL,
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
                      'name' => 'object',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 57,
            'endLine' => 57,
            'startColumn' => 28,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns true if the UUID is equal to the provided object
 *
 * The result is true if and only if the argument is not null, is a UUID object, has the same variant, and contains
 * the same value, bit-for-bit, as the UUID.
 *
 * @param object | null $other An object to test for equality with this UUID
 *
 * @return bool True if the other object is equal to this UUID
 */',
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'aliasName' => NULL,
      ),
      'getBytes' => 
      array (
        'name' => 'getBytes',
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
 * Returns the binary string representation of the UUID
 *
 * @return non-empty-string
 *
 * @pure
 */',
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'aliasName' => NULL,
      ),
      'getFields' => 
      array (
        'name' => 'getFields',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Ramsey\\Uuid\\Fields\\FieldsInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the fields that comprise this UUID
 */',
        'startLine' => 71,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'aliasName' => NULL,
      ),
      'getHex' => 
      array (
        'name' => 'getHex',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Ramsey\\Uuid\\Type\\Hexadecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the hexadecimal representation of the UUID
 */',
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'aliasName' => NULL,
      ),
      'getInteger' => 
      array (
        'name' => 'getInteger',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Ramsey\\Uuid\\Type\\Integer',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the integer representation of the UUID
 */',
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 48,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'aliasName' => NULL,
      ),
      'getUrn' => 
      array (
        'name' => 'getUrn',
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
 * Returns the string standard representation of the UUID as a URN
 *
 * @link http://en.wikipedia.org/wiki/Uniform_Resource_Name Uniform Resource Name
 * @link https://www.rfc-editor.org/rfc/rfc9562.html#section-4 RFC 9562, 4. UUID Format
 * @link https://www.rfc-editor.org/rfc/rfc9562.html#section-7 RFC 9562, 7. IANA Considerations
 * @link https://www.rfc-editor.org/rfc/rfc4122.html#section-3 RFC 4122, 3. Namespace Registration Template
 */',
        'startLine' => 91,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'aliasName' => NULL,
      ),
      'toString' => 
      array (
        'name' => 'toString',
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
 * Returns the string standard representation of the UUID
 *
 * @return non-empty-string
 *
 * @pure
 */',
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
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
 * Casts the UUID to the string standard representation
 *
 * @return non-empty-string
 *
 * @pure
 */',
        'startLine' => 109,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Ramsey\\Uuid',
        'declaringClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'implementingClassName' => 'Ramsey\\Uuid\\UuidInterface',
        'currentClassName' => 'Ramsey\\Uuid\\UuidInterface',
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