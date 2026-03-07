<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../ezyang/htmlpurifier/library/HTMLPurifier/Config.php-PHPStan\BetterReflection\Reflection\ReflectionClass-HTMLPurifier_Config
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-eaf0a89e8713a0d85eb1f6efc419171b3246f2699e2e330e1cb3d09c8d12bc6c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'HTMLPurifier_Config',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../ezyang/htmlpurifier/library/HTMLPurifier/Config.php',
      ),
    ),
    'namespace' => NULL,
    'name' => 'HTMLPurifier_Config',
    'shortName' => 'HTMLPurifier_Config',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Configuration object that triggers customizable behavior.
 *
 * @warning This class is strongly defined: that means that the class
 *          will fail if an undefined directive is retrieved or set.
 *
 * @note Many classes that could (although many times don\'t) use the
 *       configuration object make it a mandatory parameter.  This is
 *       because a configuration object should always be forwarded,
 *       otherwise, you run the risk of missing a parameter and then
 *       being stumped when a configuration directive doesn\'t work.
 *
 * @todo Reconsider some of the public member variables
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 922,
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
      'version' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'version',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'4.19.0\'',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 18,
            'startFilePos' => 742,
            'endTokenPos' => 18,
            'endFilePos' => 749,
          ),
        ),
        'docComment' => '/**
 * HTML Purifier\'s version
 * @type string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'autoFinalize' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'autoFinalize',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\true',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 29,
            'startFilePos' => 909,
            'endTokenPos' => 29,
            'endFilePos' => 912,
          ),
        ),
        'docComment' => '/**
 * Whether or not to automatically finalize
 * the object if a read operation is done.
 * @type bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'serials' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'serials',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 42,
            'startFilePos' => 1120,
            'endTokenPos' => 44,
            'endFilePos' => 1126,
          ),
        ),
        'docComment' => '/**
 * Namespace indexed array of serials for specific namespaces.
 * @see getSerial() for more info.
 * @type string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'serial' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'serial',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Serial for entire configuration object.
 * @type string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'parser' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'parser',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\null',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 62,
            'startFilePos' => 1351,
            'endTokenPos' => 62,
            'endFilePos' => 1354,
          ),
        ),
        'docComment' => '/**
 * Parser for variables.
 * @type HTMLPurifier_VarParser_Flexible
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'def' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'def',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Reference HTMLPurifier_ConfigSchema for value checking.
 * @type HTMLPurifier_ConfigSchema
 * @note This is public for introspective purposes. Please don\'t
 *       abuse!
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 16,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'definitions' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'definitions',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indexed array of definitions.
 * @type HTMLPurifier_Definition[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'finalized' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'finalized',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 72,
            'endLine' => 72,
            'startTokenPos' => 87,
            'startFilePos' => 1808,
            'endTokenPos' => 87,
            'endFilePos' => 1812,
          ),
        ),
        'docComment' => '/**
 * Whether or not config is finalized.
 * @type bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'plist' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'plist',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Property list containing configuration directives.
 * @type array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 78,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'aliasMode' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'aliasMode',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Whether or not a set is taking place due to an alias lookup.
 * @type bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'chatty' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'chatty',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\true',
          'attributes' => 
          array (
            'startLine' => 92,
            'endLine' => 92,
            'startTokenPos' => 112,
            'startFilePos' => 2281,
            'endTokenPos' => 112,
            'endFilePos' => 2284,
          ),
        ),
        'docComment' => '/**
 * Set to false if you do not want line and file numbers in errors.
 * (useful when unit testing).  This will also compress some errors
 * and exceptions.
 * @type bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 92,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'lock' => 
      array (
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'name' => 'lock',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Current lock; only gets to this namespace are allowed.
 * @type string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 18,
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
          'definition' => 
          array (
            'name' => 'definition',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 106,
            'endLine' => 106,
            'startColumn' => 33,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 106,
                'endLine' => 106,
                'startTokenPos' => 137,
                'startFilePos' => 2658,
                'endTokenPos' => 137,
                'endFilePos' => 2661,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 106,
            'endLine' => 106,
            'startColumn' => 46,
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
 * Constructor
 * @param HTMLPurifier_ConfigSchema $definition ConfigSchema that defines
 * what directives are allowed.
 * @param HTMLPurifier_PropertyList $parent
 */',
        'startLine' => 106,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 35,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'schema' => 
          array (
            'name' => 'schema',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 123,
                'endLine' => 123,
                'startTokenPos' => 216,
                'startFilePos' => 3490,
                'endTokenPos' => 216,
                'endFilePos' => 3493,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 44,
            'endColumn' => 57,
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
 * Convenience constructor that creates a config object based on a mixed var
 * @param mixed $config Variable that defines the state of the config
 *                      object. Can be: a HTMLPurifier_Config() object,
 *                      an array of directives based on loadArray(),
 *                      or a string filename of an ini file.
 * @param HTMLPurifier_ConfigSchema $schema Schema object
 * @return HTMLPurifier_Config Configured object
 */',
        'startLine' => 123,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'inherit' => 
      array (
        'name' => 'inherit',
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
                'name' => 'HTMLPurifier_Config',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 145,
            'endLine' => 145,
            'startColumn' => 36,
            'endColumn' => 62,
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
 * Creates a new config object that inherits from a previous one.
 * @param HTMLPurifier_Config $config Configuration object to inherit from.
 * @return HTMLPurifier_Config object with $config as its parent.
 */',
        'startLine' => 145,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'createDefault' => 
      array (
        'name' => 'createDefault',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convenience constructor that creates a default configuration object.
 * @return HTMLPurifier_Config default object.
 */',
        'startLine' => 154,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 25,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'a' => 
          array (
            'name' => 'a',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 169,
                'endLine' => 169,
                'startTokenPos' => 422,
                'startFilePos' => 4862,
                'endTokenPos' => 422,
                'endFilePos' => 4865,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 31,
            'endColumn' => 39,
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
 * Retrieves a value from the configuration.
 *
 * @param string $key String key
 * @param mixed $a
 *
 * @return mixed
 */',
        'startLine' => 169,
        'endLine' => 211,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'getBatch' => 
      array (
        'name' => 'getBatch',
        'parameters' => 
        array (
          'namespace' => 
          array (
            'name' => 'namespace',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 220,
            'endLine' => 220,
            'startColumn' => 30,
            'endColumn' => 39,
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
 * Retrieves an array of directives to values from a given namespace
 *
 * @param string $namespace String namespace
 *
 * @return array
 */',
        'startLine' => 220,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'getBatchSerial' => 
      array (
        'name' => 'getBatchSerial',
        'parameters' => 
        array (
          'namespace' => 
          array (
            'name' => 'namespace',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 247,
            'endLine' => 247,
            'startColumn' => 36,
            'endColumn' => 45,
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
 * Returns a SHA-1 signature of a segment of the configuration object
 * that uniquely identifies that particular configuration
 *
 * @param string $namespace Namespace to get serial for
 *
 * @return string
 * @note Revision is handled specially and is removed from the batch
 *       before processing!
 */',
        'startLine' => 247,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'getSerial' => 
      array (
        'name' => 'getSerial',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a SHA-1 signature for the entire configuration object
 * that uniquely identifies that particular configuration
 *
 * @return string
 */',
        'startLine' => 263,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'getAll' => 
      array (
        'name' => 'getAll',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieves all directives, organized by namespace
 *
 * @warning This is a pretty inefficient function, avoid if you can
 */',
        'startLine' => 276,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'set' => 
      array (
        'name' => 'set',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 296,
            'endLine' => 296,
            'startColumn' => 25,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 296,
            'endLine' => 296,
            'startColumn' => 31,
            'endColumn' => 36,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'a' => 
          array (
            'name' => 'a',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 296,
                'endLine' => 296,
                'startTokenPos' => 1047,
                'startFilePos' => 8663,
                'endTokenPos' => 1047,
                'endFilePos' => 8666,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 296,
            'endLine' => 296,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets a value to configuration.
 *
 * @param string $key key
 * @param mixed $value value
 * @param mixed $a
 */',
        'startLine' => 296,
        'endLine' => 381,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      '_listify' => 
      array (
        'name' => '_listify',
        'parameters' => 
        array (
          'lookup' => 
          array (
            'name' => 'lookup',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 390,
            'endLine' => 390,
            'startColumn' => 31,
            'endColumn' => 37,
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
 * Convenience function for error reporting
 *
 * @param array $lookup
 *
 * @return string
 */',
        'startLine' => 390,
        'endLine' => 397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'getHTMLDefinition' => 
      array (
        'name' => 'getHTMLDefinition',
        'parameters' => 
        array (
          'raw' => 
          array (
            'name' => 'raw',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 413,
                'endLine' => 413,
                'startTokenPos' => 1731,
                'startFilePos' => 12762,
                'endTokenPos' => 1731,
                'endFilePos' => 12766,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 413,
            'endLine' => 413,
            'startColumn' => 39,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'optimized' => 
          array (
            'name' => 'optimized',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 413,
                'endLine' => 413,
                'startTokenPos' => 1738,
                'startFilePos' => 12782,
                'endTokenPos' => 1738,
                'endFilePos' => 12786,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 413,
            'endLine' => 413,
            'startColumn' => 53,
            'endColumn' => 70,
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
 * Retrieves object reference to the HTML definition.
 *
 * @param bool $raw Return a copy that has not been setup yet. Must be
 *             called before it\'s been setup, otherwise won\'t work.
 * @param bool $optimized If true, this method may return null, to
 *             indicate that a cached version of the modified
 *             definition object is available and no further edits
 *             are necessary.  Consider using
 *             maybeGetRawHTMLDefinition, which is more explicitly
 *             named, instead.
 *
 * @return HTMLPurifier_HTMLDefinition|null
 */',
        'startLine' => 413,
        'endLine' => 416,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'getCSSDefinition' => 
      array (
        'name' => 'getCSSDefinition',
        'parameters' => 
        array (
          'raw' => 
          array (
            'name' => 'raw',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 432,
                'endLine' => 432,
                'startTokenPos' => 1773,
                'startFilePos' => 13552,
                'endTokenPos' => 1773,
                'endFilePos' => 13556,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 432,
            'endLine' => 432,
            'startColumn' => 38,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'optimized' => 
          array (
            'name' => 'optimized',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 432,
                'endLine' => 432,
                'startTokenPos' => 1780,
                'startFilePos' => 13572,
                'endTokenPos' => 1780,
                'endFilePos' => 13576,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 432,
            'endLine' => 432,
            'startColumn' => 52,
            'endColumn' => 69,
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
 * Retrieves object reference to the CSS definition
 *
 * @param bool $raw Return a copy that has not been setup yet. Must be
 *             called before it\'s been setup, otherwise won\'t work.
 * @param bool $optimized If true, this method may return null, to
 *             indicate that a cached version of the modified
 *             definition object is available and no further edits
 *             are necessary.  Consider using
 *             maybeGetRawCSSDefinition, which is more explicitly
 *             named, instead.
 *
 * @return HTMLPurifier_CSSDefinition|null
 */',
        'startLine' => 432,
        'endLine' => 435,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'getURIDefinition' => 
      array (
        'name' => 'getURIDefinition',
        'parameters' => 
        array (
          'raw' => 
          array (
            'name' => 'raw',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 451,
                'endLine' => 451,
                'startTokenPos' => 1815,
                'startFilePos' => 14341,
                'endTokenPos' => 1815,
                'endFilePos' => 14345,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 451,
            'endLine' => 451,
            'startColumn' => 38,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'optimized' => 
          array (
            'name' => 'optimized',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 451,
                'endLine' => 451,
                'startTokenPos' => 1822,
                'startFilePos' => 14361,
                'endTokenPos' => 1822,
                'endFilePos' => 14365,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 451,
            'endLine' => 451,
            'startColumn' => 52,
            'endColumn' => 69,
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
 * Retrieves object reference to the URI definition
 *
 * @param bool $raw Return a copy that has not been setup yet. Must be
 *             called before it\'s been setup, otherwise won\'t work.
 * @param bool $optimized If true, this method may return null, to
 *             indicate that a cached version of the modified
 *             definition object is available and no further edits
 *             are necessary.  Consider using
 *             maybeGetRawURIDefinition, which is more explicitly
 *             named, instead.
 *
 * @return HTMLPurifier_URIDefinition|null
 */',
        'startLine' => 451,
        'endLine' => 454,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'getDefinition' => 
      array (
        'name' => 'getDefinition',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 473,
            'endLine' => 473,
            'startColumn' => 35,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'raw' => 
          array (
            'name' => 'raw',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 473,
                'endLine' => 473,
                'startTokenPos' => 1860,
                'startFilePos' => 15312,
                'endTokenPos' => 1860,
                'endFilePos' => 15316,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 473,
            'endLine' => 473,
            'startColumn' => 42,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'optimized' => 
          array (
            'name' => 'optimized',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 473,
                'endLine' => 473,
                'startTokenPos' => 1867,
                'startFilePos' => 15332,
                'endTokenPos' => 1867,
                'endFilePos' => 15336,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 473,
            'endLine' => 473,
            'startColumn' => 56,
            'endColumn' => 73,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieves a definition
 *
 * @param string $type Type of definition: HTML, CSS, etc
 * @param bool $raw Whether or not definition should be returned raw
 * @param bool $optimized Only has an effect when $raw is true.  Whether
 *        or not to return null if the result is already present in
 *        the cache.  This is off by default for backwards
 *        compatibility reasons, but you need to do things this
 *        way in order to ensure that caching is done properly.
 *        Check out enduser-customize.html for more details.
 *        We probably won\'t ever change this default, as much as the
 *        maybe semantics is the "right thing to do."
 *
 * @throws HTMLPurifier_Exception
 * @return HTMLPurifier_Definition|null
 */',
        'startLine' => 473,
        'endLine' => 616,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'initDefinition' => 
      array (
        'name' => 'initDefinition',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 626,
            'endLine' => 626,
            'startColumn' => 37,
            'endColumn' => 41,
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
 * Initialise definition
 *
 * @param string $type What type of definition to create
 *
 * @return HTMLPurifier_CSSDefinition|HTMLPurifier_HTMLDefinition|HTMLPurifier_URIDefinition
 * @throws HTMLPurifier_Exception
 */',
        'startLine' => 626,
        'endLine' => 642,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'maybeGetRawDefinition' => 
      array (
        'name' => 'maybeGetRawDefinition',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 644,
            'endLine' => 644,
            'startColumn' => 43,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 644,
        'endLine' => 647,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'maybeGetRawHTMLDefinition' => 
      array (
        'name' => 'maybeGetRawHTMLDefinition',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return HTMLPurifier_HTMLDefinition|null
 */',
        'startLine' => 652,
        'endLine' => 655,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'maybeGetRawCSSDefinition' => 
      array (
        'name' => 'maybeGetRawCSSDefinition',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return HTMLPurifier_CSSDefinition|null
 */',
        'startLine' => 660,
        'endLine' => 663,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'maybeGetRawURIDefinition' => 
      array (
        'name' => 'maybeGetRawURIDefinition',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return HTMLPurifier_URIDefinition|null
 */',
        'startLine' => 668,
        'endLine' => 671,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'loadArray' => 
      array (
        'name' => 'loadArray',
        'parameters' => 
        array (
          'config_array' => 
          array (
            'name' => 'config_array',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 679,
            'endLine' => 679,
            'startColumn' => 31,
            'endColumn' => 43,
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
 * Loads configuration values from an array with the following structure:
 * Namespace.Directive => Value
 *
 * @param array $config_array Configuration associative array
 */',
        'startLine' => 679,
        'endLine' => 696,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'getAllowedDirectivesForForm' => 
      array (
        'name' => 'getAllowedDirectivesForForm',
        'parameters' => 
        array (
          'allowed' => 
          array (
            'name' => 'allowed',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 708,
            'endLine' => 708,
            'startColumn' => 56,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'schema' => 
          array (
            'name' => 'schema',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 708,
                'endLine' => 708,
                'startTokenPos' => 3132,
                'startFilePos' => 24428,
                'endTokenPos' => 3132,
                'endFilePos' => 24431,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 708,
            'endLine' => 708,
            'startColumn' => 66,
            'endColumn' => 79,
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
 * Returns a list of array(namespace, directive) for all directives
 * that are allowed in a web-form context as per an allowed
 * namespaces/directives list.
 *
 * @param array $allowed List of allowed namespaces/directives
 * @param HTMLPurifier_ConfigSchema $schema Schema to use, if not global copy
 *
 * @return array
 */',
        'startLine' => 708,
        'endLine' => 754,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'loadArrayFromForm' => 
      array (
        'name' => 'loadArrayFromForm',
        'parameters' => 
        array (
          'array' => 
          array (
            'name' => 'array',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 768,
            'endLine' => 768,
            'startColumn' => 46,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'index' => 
          array (
            'name' => 'index',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 768,
                'endLine' => 768,
                'startTokenPos' => 3531,
                'startFilePos' => 26661,
                'endTokenPos' => 3531,
                'endFilePos' => 26665,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 768,
            'endLine' => 768,
            'startColumn' => 54,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'allowed' => 
          array (
            'name' => 'allowed',
            'default' => 
            array (
              'code' => '\\true',
              'attributes' => 
              array (
                'startLine' => 768,
                'endLine' => 768,
                'startTokenPos' => 3538,
                'startFilePos' => 26679,
                'endTokenPos' => 3538,
                'endFilePos' => 26682,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 768,
            'endLine' => 768,
            'startColumn' => 70,
            'endColumn' => 84,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'mq_fix' => 
          array (
            'name' => 'mq_fix',
            'default' => 
            array (
              'code' => '\\true',
              'attributes' => 
              array (
                'startLine' => 768,
                'endLine' => 768,
                'startTokenPos' => 3545,
                'startFilePos' => 26695,
                'endTokenPos' => 3545,
                'endFilePos' => 26698,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 768,
            'endLine' => 768,
            'startColumn' => 87,
            'endColumn' => 100,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'schema' => 
          array (
            'name' => 'schema',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 768,
                'endLine' => 768,
                'startTokenPos' => 3552,
                'startFilePos' => 26711,
                'endTokenPos' => 3552,
                'endFilePos' => 26714,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 768,
            'endLine' => 768,
            'startColumn' => 103,
            'endColumn' => 116,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Loads configuration values from $_GET/$_POST that were posted
 * via ConfigForm
 *
 * @param array $array $_GET or $_POST array to import
 * @param string|bool $index Index/name that the config variables are in
 * @param array|bool $allowed List of allowed namespaces/directives
 * @param bool $mq_fix Boolean whether or not to enable magic quotes fix
 * @param HTMLPurifier_ConfigSchema $schema Schema to use, if not global copy
 *
 * @return mixed
 */',
        'startLine' => 768,
        'endLine' => 773,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'mergeArrayFromForm' => 
      array (
        'name' => 'mergeArrayFromForm',
        'parameters' => 
        array (
          'array' => 
          array (
            'name' => 'array',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 783,
            'endLine' => 783,
            'startColumn' => 40,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'index' => 
          array (
            'name' => 'index',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 783,
                'endLine' => 783,
                'startTokenPos' => 3618,
                'startFilePos' => 27361,
                'endTokenPos' => 3618,
                'endFilePos' => 27365,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 783,
            'endLine' => 783,
            'startColumn' => 48,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'allowed' => 
          array (
            'name' => 'allowed',
            'default' => 
            array (
              'code' => '\\true',
              'attributes' => 
              array (
                'startLine' => 783,
                'endLine' => 783,
                'startTokenPos' => 3625,
                'startFilePos' => 27379,
                'endTokenPos' => 3625,
                'endFilePos' => 27382,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 783,
            'endLine' => 783,
            'startColumn' => 64,
            'endColumn' => 78,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'mq_fix' => 
          array (
            'name' => 'mq_fix',
            'default' => 
            array (
              'code' => '\\true',
              'attributes' => 
              array (
                'startLine' => 783,
                'endLine' => 783,
                'startTokenPos' => 3632,
                'startFilePos' => 27395,
                'endTokenPos' => 3632,
                'endFilePos' => 27398,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 783,
            'endLine' => 783,
            'startColumn' => 81,
            'endColumn' => 94,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Merges in configuration values from $_GET/$_POST to object. NOT STATIC.
 *
 * @param array $array $_GET or $_POST array to import
 * @param string|bool $index Index/name that the config variables are in
 * @param array|bool $allowed List of allowed namespaces/directives
 * @param bool $mq_fix Boolean whether or not to enable magic quotes fix
 */',
        'startLine' => 783,
        'endLine' => 787,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'prepareArrayFromForm' => 
      array (
        'name' => 'prepareArrayFromForm',
        'parameters' => 
        array (
          'array' => 
          array (
            'name' => 'array',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 801,
            'endLine' => 801,
            'startColumn' => 49,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'index' => 
          array (
            'name' => 'index',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 801,
                'endLine' => 801,
                'startTokenPos' => 3690,
                'startFilePos' => 28151,
                'endTokenPos' => 3690,
                'endFilePos' => 28155,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 801,
            'endLine' => 801,
            'startColumn' => 57,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'allowed' => 
          array (
            'name' => 'allowed',
            'default' => 
            array (
              'code' => '\\true',
              'attributes' => 
              array (
                'startLine' => 801,
                'endLine' => 801,
                'startTokenPos' => 3697,
                'startFilePos' => 28169,
                'endTokenPos' => 3697,
                'endFilePos' => 28172,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 801,
            'endLine' => 801,
            'startColumn' => 73,
            'endColumn' => 87,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'mq_fix' => 
          array (
            'name' => 'mq_fix',
            'default' => 
            array (
              'code' => '\\true',
              'attributes' => 
              array (
                'startLine' => 801,
                'endLine' => 801,
                'startTokenPos' => 3704,
                'startFilePos' => 28185,
                'endTokenPos' => 3704,
                'endFilePos' => 28188,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 801,
            'endLine' => 801,
            'startColumn' => 90,
            'endColumn' => 103,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'schema' => 
          array (
            'name' => 'schema',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 801,
                'endLine' => 801,
                'startTokenPos' => 3711,
                'startFilePos' => 28201,
                'endTokenPos' => 3711,
                'endFilePos' => 28204,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 801,
            'endLine' => 801,
            'startColumn' => 106,
            'endColumn' => 119,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares an array from a form into something usable for the more
 * strict parts of HTMLPurifier_Config
 *
 * @param array $array $_GET or $_POST array to import
 * @param string|bool $index Index/name that the config variables are in
 * @param array|bool $allowed List of allowed namespaces/directives
 * @param bool $mq_fix Boolean whether or not to enable magic quotes fix
 * @param HTMLPurifier_ConfigSchema $schema Schema to use, if not global copy
 *
 * @return array
 */',
        'startLine' => 801,
        'endLine' => 824,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'loadIni' => 
      array (
        'name' => 'loadIni',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 831,
            'endLine' => 831,
            'startColumn' => 29,
            'endColumn' => 37,
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
 * Loads configuration values from an ini file
 *
 * @param string $filename Name of ini file
 */',
        'startLine' => 831,
        'endLine' => 838,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'isFinalized' => 
      array (
        'name' => 'isFinalized',
        'parameters' => 
        array (
          'error' => 
          array (
            'name' => 'error',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 847,
                'endLine' => 847,
                'startTokenPos' => 4029,
                'startFilePos' => 29696,
                'endTokenPos' => 4029,
                'endFilePos' => 29700,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 847,
            'endLine' => 847,
            'startColumn' => 33,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks whether or not the configuration object is finalized.
 *
 * @param string|bool $error String error message, or false for no error
 *
 * @return bool
 */',
        'startLine' => 847,
        'endLine' => 853,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'autoFinalize' => 
      array (
        'name' => 'autoFinalize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Finalizes configuration only if auto finalize is on and not
 * already finalized
 */',
        'startLine' => 859,
        'endLine' => 866,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'finalize' => 
      array (
        'name' => 'finalize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Finalizes a configuration object, prohibiting further change
 */',
        'startLine' => 871,
        'endLine' => 875,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'triggerError' => 
      array (
        'name' => 'triggerError',
        'parameters' => 
        array (
          'msg' => 
          array (
            'name' => 'msg',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 884,
            'endLine' => 884,
            'startColumn' => 37,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'no' => 
          array (
            'name' => 'no',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 884,
            'endLine' => 884,
            'startColumn' => 43,
            'endColumn' => 45,
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
 * Produces a nicely formatted error message by supplying the
 * stack frame information OUTSIDE of HTMLPurifier_Config.
 *
 * @param string $msg An error message
 * @param int $no An error number
 */',
        'startLine' => 884,
        'endLine' => 906,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
        'aliasName' => NULL,
      ),
      'serialize' => 
      array (
        'name' => 'serialize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a serialized form of the configuration object that can
 * be reconstituted.
 *
 * @return string
 */',
        'startLine' => 914,
        'endLine' => 920,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier_Config',
        'implementingClassName' => 'HTMLPurifier_Config',
        'currentClassName' => 'HTMLPurifier_Config',
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