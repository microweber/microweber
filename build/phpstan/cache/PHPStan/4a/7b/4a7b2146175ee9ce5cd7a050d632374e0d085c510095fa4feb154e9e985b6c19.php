<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/InteractsWithMouse.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Dusk\Concerns\InteractsWithMouse
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-04ba5c5ddda13a2d4b5ca267a1ba3cbbe111f9ca5738701600e6dcaa3850e076-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/InteractsWithMouse.php',
      ),
    ),
    'namespace' => 'Laravel\\Dusk\\Concerns',
    'name' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
    'shortName' => 'InteractsWithMouse',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 187,
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
      'moveMouse' => 
      array (
        'name' => 'moveMouse',
        'parameters' => 
        array (
          'xOffset' => 
          array (
            'name' => 'xOffset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 31,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'yOffset' => 
          array (
            'name' => 'yOffset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 41,
            'endColumn' => 48,
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
 * Move the mouse by offset X and Y.
 *
 * @param  int  $xOffset
 * @param  int  $yOffset
 * @return $this
 */',
        'startLine' => 22,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'aliasName' => NULL,
      ),
      'mouseover' => 
      array (
        'name' => 'mouseover',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 31,
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
 * Move the mouse over the given selector.
 *
 * @param  string  $selector
 * @return $this
 */',
        'startLine' => 37,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'aliasName' => NULL,
      ),
      'click' => 
      array (
        'name' => 'click',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 52,
                'endLine' => 52,
                'startTokenPos' => 161,
                'startFilePos' => 1228,
                'endTokenPos' => 161,
                'endFilePos' => 1231,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 27,
            'endColumn' => 42,
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
 * Click the element at the given selector.
 *
 * @param  string|null  $selector
 * @return $this
 */',
        'startLine' => 52,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'aliasName' => NULL,
      ),
      'clickAtPoint' => 
      array (
        'name' => 'clickAtPoint',
        'parameters' => 
        array (
          'x' => 
          array (
            'name' => 'x',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 80,
            'endLine' => 80,
            'startColumn' => 34,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'y' => 
          array (
            'name' => 'y',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 80,
            'endLine' => 80,
            'startColumn' => 38,
            'endColumn' => 39,
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
 * Click the topmost element at the given pair of coordinates.
 *
 * @param  int  $x
 * @param  int  $y
 * @return $this
 */',
        'startLine' => 80,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'aliasName' => NULL,
      ),
      'clickAtXPath' => 
      array (
        'name' => 'clickAtXPath',
        'parameters' => 
        array (
          'expression' => 
          array (
            'name' => 'expression',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 34,
            'endColumn' => 44,
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
 * Click the element at the given XPath expression.
 *
 * @param  string  $expression
 * @return $this
 */',
        'startLine' => 93,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'aliasName' => NULL,
      ),
      'clickAndHold' => 
      array (
        'name' => 'clickAndHold',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 108,
                'endLine' => 108,
                'startTokenPos' => 375,
                'startFilePos' => 2599,
                'endTokenPos' => 375,
                'endFilePos' => 2602,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 108,
            'endLine' => 108,
            'startColumn' => 34,
            'endColumn' => 49,
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
 * Perform a mouse click and hold the mouse button down at the given selector.
 *
 * @param  string|null  $selector
 * @return $this
 */',
        'startLine' => 108,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'aliasName' => NULL,
      ),
      'doubleClick' => 
      array (
        'name' => 'doubleClick',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 127,
                'endLine' => 127,
                'startTokenPos' => 468,
                'startFilePos' => 3107,
                'endTokenPos' => 468,
                'endFilePos' => 3110,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 127,
            'endLine' => 127,
            'startColumn' => 33,
            'endColumn' => 48,
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
 * Double click the element at the given selector.
 *
 * @param  string|null  $selector
 * @return $this
 */',
        'startLine' => 127,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'aliasName' => NULL,
      ),
      'rightClick' => 
      array (
        'name' => 'rightClick',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 146,
                'endLine' => 146,
                'startTokenPos' => 561,
                'startFilePos' => 3611,
                'endTokenPos' => 561,
                'endFilePos' => 3614,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 146,
            'endLine' => 146,
            'startColumn' => 32,
            'endColumn' => 47,
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
 * Right click the element at the given selector.
 *
 * @param  string|null  $selector
 * @return $this
 */',
        'startLine' => 146,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'aliasName' => NULL,
      ),
      'controlClick' => 
      array (
        'name' => 'controlClick',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 165,
                'endLine' => 165,
                'startTokenPos' => 654,
                'startFilePos' => 4121,
                'endTokenPos' => 654,
                'endFilePos' => 4124,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 34,
            'endColumn' => 49,
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
 * Control click the element at the given selector.
 *
 * @param  string|null  $selector
 * @return $this
 */',
        'startLine' => 165,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'aliasName' => NULL,
      ),
      'releaseMouse' => 
      array (
        'name' => 'releaseMouse',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Release the currently clicked mouse button.
 *
 * @return $this
 */',
        'startLine' => 181,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithMouse',
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