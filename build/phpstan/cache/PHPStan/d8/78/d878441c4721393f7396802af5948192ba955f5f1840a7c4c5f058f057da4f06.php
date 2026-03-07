<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../doctrine/dbal/src/Types/Type.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Doctrine\DBAL\Types\Type
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1232e1778f99abe8fc1ed01a320bd0d9d3b528309904747145dbdd6709397ea1-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Doctrine\\DBAL\\Types\\Type',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../doctrine/dbal/src/Types/Type.php',
      ),
    ),
    'namespace' => 'Doctrine\\DBAL\\Types',
    'name' => 'Doctrine\\DBAL\\Types\\Type',
    'shortName' => 'Type',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * The base class for so-called Doctrine mapping types.
 *
 * A Type object is obtained by calling the static {@see getType()} method.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 223,
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
      'BUILTIN_TYPES_MAP' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'name' => 'BUILTIN_TYPES_MAP',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\\Doctrine\\DBAL\\Types\\Types::ASCII_STRING => \\Doctrine\\DBAL\\Types\\AsciiStringType::class, \\Doctrine\\DBAL\\Types\\Types::BIGINT => \\Doctrine\\DBAL\\Types\\BigIntType::class, \\Doctrine\\DBAL\\Types\\Types::BINARY => \\Doctrine\\DBAL\\Types\\BinaryType::class, \\Doctrine\\DBAL\\Types\\Types::BLOB => \\Doctrine\\DBAL\\Types\\BlobType::class, \\Doctrine\\DBAL\\Types\\Types::BOOLEAN => \\Doctrine\\DBAL\\Types\\BooleanType::class, \\Doctrine\\DBAL\\Types\\Types::DATE_MUTABLE => \\Doctrine\\DBAL\\Types\\DateType::class, \\Doctrine\\DBAL\\Types\\Types::DATE_IMMUTABLE => \\Doctrine\\DBAL\\Types\\DateImmutableType::class, \\Doctrine\\DBAL\\Types\\Types::DATEINTERVAL => \\Doctrine\\DBAL\\Types\\DateIntervalType::class, \\Doctrine\\DBAL\\Types\\Types::DATETIME_MUTABLE => \\Doctrine\\DBAL\\Types\\DateTimeType::class, \\Doctrine\\DBAL\\Types\\Types::DATETIME_IMMUTABLE => \\Doctrine\\DBAL\\Types\\DateTimeImmutableType::class, \\Doctrine\\DBAL\\Types\\Types::DATETIMETZ_MUTABLE => \\Doctrine\\DBAL\\Types\\DateTimeTzType::class, \\Doctrine\\DBAL\\Types\\Types::DATETIMETZ_IMMUTABLE => \\Doctrine\\DBAL\\Types\\DateTimeTzImmutableType::class, \\Doctrine\\DBAL\\Types\\Types::DECIMAL => \\Doctrine\\DBAL\\Types\\DecimalType::class, \\Doctrine\\DBAL\\Types\\Types::ENUM => \\Doctrine\\DBAL\\Types\\EnumType::class, \\Doctrine\\DBAL\\Types\\Types::FLOAT => \\Doctrine\\DBAL\\Types\\FloatType::class, \\Doctrine\\DBAL\\Types\\Types::GUID => \\Doctrine\\DBAL\\Types\\GuidType::class, \\Doctrine\\DBAL\\Types\\Types::INTEGER => \\Doctrine\\DBAL\\Types\\IntegerType::class, \\Doctrine\\DBAL\\Types\\Types::JSON => \\Doctrine\\DBAL\\Types\\JsonType::class, \\Doctrine\\DBAL\\Types\\Types::SIMPLE_ARRAY => \\Doctrine\\DBAL\\Types\\SimpleArrayType::class, \\Doctrine\\DBAL\\Types\\Types::SMALLFLOAT => \\Doctrine\\DBAL\\Types\\SmallFloatType::class, \\Doctrine\\DBAL\\Types\\Types::SMALLINT => \\Doctrine\\DBAL\\Types\\SmallIntType::class, \\Doctrine\\DBAL\\Types\\Types::STRING => \\Doctrine\\DBAL\\Types\\StringType::class, \\Doctrine\\DBAL\\Types\\Types::TEXT => \\Doctrine\\DBAL\\Types\\TextType::class, \\Doctrine\\DBAL\\Types\\Types::TIME_MUTABLE => \\Doctrine\\DBAL\\Types\\TimeType::class, \\Doctrine\\DBAL\\Types\\Types::TIME_IMMUTABLE => \\Doctrine\\DBAL\\Types\\TimeImmutableType::class]',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 49,
            'startTokenPos' => 57,
            'startFilePos' => 470,
            'endTokenPos' => 334,
            'endFilePos' => 1980,
          ),
        ),
        'docComment' => '/**
 * The map of supported doctrine mapping types.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
      'typeRegistry' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'name' => 'typeRegistry',
        'modifiers' => 20,
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
                  'name' => 'Doctrine\\DBAL\\Types\\TypeRegistry',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 348,
            'startFilePos' => 2033,
            'endTokenPos' => 348,
            'endFilePos' => 2036,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 54,
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
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/** @internal Do not instantiate directly - use {@see Type::addType()} method instead. */',
        'startLine' => 54,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'convertToDatabaseValue' => 
      array (
        'name' => 'convertToDatabaseValue',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 44,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'platform' => 
          array (
            'name' => 'platform',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Platforms\\AbstractPlatform',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 58,
            'endColumn' => 83,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Converts a value from its PHP representation to its database representation
 * of this type.
 *
 * @param mixed            $value    The value to convert.
 * @param AbstractPlatform $platform The currently used database platform.
 *
 * @return mixed The database representation of the value.
 *
 * @throws ConversionException
 */',
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'convertToPHPValue' => 
      array (
        'name' => 'convertToPHPValue',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 39,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'platform' => 
          array (
            'name' => 'platform',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Platforms\\AbstractPlatform',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 53,
            'endColumn' => 78,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Converts a value from its database representation to its PHP representation
 * of this type.
 *
 * @param mixed            $value    The value to convert.
 * @param AbstractPlatform $platform The currently used database platform.
 *
 * @return mixed The PHP representation of the value.
 *
 * @throws ConversionException
 */',
        'startLine' => 85,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'getSQLDeclaration' => 
      array (
        'name' => 'getSQLDeclaration',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
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
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 48,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'platform' => 
          array (
            'name' => 'platform',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Platforms\\AbstractPlatform',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 63,
            'endColumn' => 88,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
 * Gets the SQL declaration snippet for a column of this type.
 *
 * @param array<string, mixed> $column   The column definition
 * @param AbstractPlatform     $platform The currently used database platform.
 */',
        'startLine' => 96,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 98,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'getTypeRegistry' => 
      array (
        'name' => 'getTypeRegistry',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Types\\TypeRegistry',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 98,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 49,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'createTypeRegistry' => 
      array (
        'name' => 'createTypeRegistry',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Types\\TypeRegistry',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 103,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'getType' => 
      array (
        'name' => 'getType',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 121,
            'endLine' => 121,
            'startColumn' => 36,
            'endColumn' => 47,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Factory method to create type instances.
 *
 * @param string $name The name of the type.
 *
 * @throws Exception
 */',
        'startLine' => 121,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'lookupName' => 
      array (
        'name' => 'lookupName',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 131,
            'endLine' => 131,
            'startColumn' => 39,
            'endColumn' => 48,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Finds a name for the given type.
 *
 * @throws Exception
 */',
        'startLine' => 131,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'addType' => 
      array (
        'name' => 'addType',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 144,
            'endLine' => 144,
            'startColumn' => 36,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'className' => 
          array (
            'name' => 'className',
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
            'startLine' => 144,
            'endLine' => 144,
            'startColumn' => 50,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds a custom type to the type map.
 *
 * @param string             $name      The name of the type.
 * @param class-string<Type> $className The class name of the custom type.
 *
 * @throws Exception
 */',
        'startLine' => 144,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'hasType' => 
      array (
        'name' => 'hasType',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 156,
            'endLine' => 156,
            'startColumn' => 36,
            'endColumn' => 47,
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
 * Checks if exists support for a type.
 *
 * @param string $name The name of the type.
 *
 * @return bool TRUE if type is supported; FALSE otherwise.
 */',
        'startLine' => 156,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'overrideType' => 
      array (
        'name' => 'overrideType',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 168,
            'endLine' => 168,
            'startColumn' => 41,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'className' => 
          array (
            'name' => 'className',
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
            'startLine' => 168,
            'endLine' => 168,
            'startColumn' => 55,
            'endColumn' => 71,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Overrides an already defined type to use a different implementation.
 *
 * @param class-string<Type> $className
 *
 * @throws Exception
 */',
        'startLine' => 168,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'getBindingType' => 
      array (
        'name' => 'getBindingType',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\ParameterType',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the (preferred) binding type for values of this type that
 * can be used when binding parameters to prepared statements.
 */',
        'startLine' => 177,
        'endLine' => 180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'getTypesMap' => 
      array (
        'name' => 'getTypesMap',
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
 * Gets the types array map which holds all registered types and the corresponding
 * type class
 *
 * @return array<string, string>
 */',
        'startLine' => 188,
        'endLine' => 196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'convertToDatabaseValueSQL' => 
      array (
        'name' => 'convertToDatabaseValueSQL',
        'parameters' => 
        array (
          'sqlExpr' => 
          array (
            'name' => 'sqlExpr',
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
            'startLine' => 201,
            'endLine' => 201,
            'startColumn' => 47,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'platform' => 
          array (
            'name' => 'platform',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Platforms\\AbstractPlatform',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 201,
            'endLine' => 201,
            'startColumn' => 64,
            'endColumn' => 89,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
 * Modifies the SQL expression (identifier, parameter) to convert to a database value.
 */',
        'startLine' => 201,
        'endLine' => 204,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'convertToPHPValueSQL' => 
      array (
        'name' => 'convertToPHPValueSQL',
        'parameters' => 
        array (
          'sqlExpr' => 
          array (
            'name' => 'sqlExpr',
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
            'startLine' => 209,
            'endLine' => 209,
            'startColumn' => 42,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'platform' => 
          array (
            'name' => 'platform',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Platforms\\AbstractPlatform',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 209,
            'endLine' => 209,
            'startColumn' => 59,
            'endColumn' => 84,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
 * Modifies the SQL expression (identifier, parameter) to convert to a PHP value.
 */',
        'startLine' => 209,
        'endLine' => 212,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'aliasName' => NULL,
      ),
      'getMappedDatabaseTypes' => 
      array (
        'name' => 'getMappedDatabaseTypes',
        'parameters' => 
        array (
          'platform' => 
          array (
            'name' => 'platform',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Platforms\\AbstractPlatform',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 44,
            'endColumn' => 69,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets an array of database types that map to this Doctrine type.
 *
 * @return array<int, string>
 */',
        'startLine' => 219,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL\\Types',
        'declaringClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'implementingClassName' => 'Doctrine\\DBAL\\Types\\Type',
        'currentClassName' => 'Doctrine\\DBAL\\Types\\Type',
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