<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/Utils/lib/phpQuery.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Callback
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-5de5743ba718d220c89765e4bcaf749899c4b147ae367f1b34a5a2e7f7fee534',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Callback',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/Utils/lib/phpQuery.php',
      ),
    ),
    'namespace' => NULL,
    'name' => 'Callback',
    'shortName' => 'Callback',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Callback class introduces currying-like pattern.
 *
 * Example:
 * function foo($param1, $param2, $param3) {
 *   var_dump($param1, $param2, $param3);
 * }
 * $fooCurried = new Callback(\'foo\',
 *   \'param1 is now statically set\',
 *   new CallbackParam, new CallbackParam
 * );
 * phpQuery::callbackRun($fooCurried,
 * 	array(\'param2 value\', \'param3 value\'
 * );
 *
 * Callback class is supported in all phpQuery methods which accepts callbacks.
 *
 * @link http://code.google.com/p/phpquery/wiki/Callbacks#Param_Structures
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 *
 * @TODO??? return fake forwarding function created via create_function
 * @TODO honor paramStructure
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 1164,
    'endLine' => 1204,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'ICallbackNamed',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'callback' => 
      array (
        'declaringClassName' => 'Callback',
        'implementingClassName' => 'Callback',
        'name' => 'callback',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\null',
          'attributes' => 
          array (
            'startLine' => 1166,
            'endLine' => 1166,
            'startTokenPos' => 6800,
            'startFilePos' => 40047,
            'endTokenPos' => 6800,
            'endFilePos' => 40050,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 1166,
        'endLine' => 1166,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'params' => 
      array (
        'declaringClassName' => 'Callback',
        'implementingClassName' => 'Callback',
        'name' => 'params',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\null',
          'attributes' => 
          array (
            'startLine' => 1167,
            'endLine' => 1167,
            'startTokenPos' => 6809,
            'startFilePos' => 40074,
            'endTokenPos' => 6809,
            'endFilePos' => 40077,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 1167,
        'endLine' => 1167,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'name' => 
      array (
        'declaringClassName' => 'Callback',
        'implementingClassName' => 'Callback',
        'name' => 'name',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 1168,
        'endLine' => 1168,
        'startColumn' => 5,
        'endColumn' => 20,
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
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1170,
            'endLine' => 1170,
            'startColumn' => 33,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'param1' => 
          array (
            'name' => 'param1',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 1170,
                'endLine' => 1170,
                'startTokenPos' => 6830,
                'startFilePos' => 40155,
                'endTokenPos' => 6830,
                'endFilePos' => 40158,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1170,
            'endLine' => 1170,
            'startColumn' => 44,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'param2' => 
          array (
            'name' => 'param2',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 1170,
                'endLine' => 1170,
                'startTokenPos' => 6837,
                'startFilePos' => 40171,
                'endTokenPos' => 6837,
                'endFilePos' => 40174,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1170,
            'endLine' => 1170,
            'startColumn' => 60,
            'endColumn' => 73,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'param3' => 
          array (
            'name' => 'param3',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 1170,
                'endLine' => 1170,
                'startTokenPos' => 6844,
                'startFilePos' => 40187,
                'endTokenPos' => 6844,
                'endFilePos' => 40190,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1170,
            'endLine' => 1170,
            'startColumn' => 76,
            'endColumn' => 89,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1170,
        'endLine' => 1180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'Callback',
        'implementingClassName' => 'Callback',
        'currentClassName' => 'Callback',
        'aliasName' => NULL,
      ),
      'getName' => 
      array (
        'name' => 'getName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1182,
        'endLine' => 1185,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'Callback',
        'implementingClassName' => 'Callback',
        'currentClassName' => 'Callback',
        'aliasName' => NULL,
      ),
      'hasName' => 
      array (
        'name' => 'hasName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1187,
        'endLine' => 1190,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'Callback',
        'implementingClassName' => 'Callback',
        'currentClassName' => 'Callback',
        'aliasName' => NULL,
      ),
      'setName' => 
      array (
        'name' => 'setName',
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
            'startLine' => 1192,
            'endLine' => 1192,
            'startColumn' => 29,
            'endColumn' => 33,
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
        'startLine' => 1192,
        'endLine' => 1197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'Callback',
        'implementingClassName' => 'Callback',
        'currentClassName' => 'Callback',
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