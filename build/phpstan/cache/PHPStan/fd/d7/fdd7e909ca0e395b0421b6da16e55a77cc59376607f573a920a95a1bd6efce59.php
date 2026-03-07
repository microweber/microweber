<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/HtmlSanitizerConfig.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-bb69d38824105412d3f222c887fa6a8f369163db66446ab7d82edc771608f51f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/HtmlSanitizerConfig.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\HtmlSanitizer',
    'name' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
    'shortName' => 'HtmlSanitizerConfig',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 544,
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
      'defaultAction' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'defaultAction',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerAction',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => '\\Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerAction::Drop',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 35,
            'startFilePos' => 574,
            'endTokenPos' => 37,
            'endFilePos' => 598,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 75,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'droppedElements' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'droppedElements',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 50,
            'startFilePos' => 734,
            'endTokenPos' => 51,
            'endFilePos' => 735,
          ),
        ),
        'docComment' => '/**
 * Elements that should be removed.
 *
 * @var array<string, true>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'blockedElements' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'blockedElements',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 64,
            'startFilePos' => 909,
            'endTokenPos' => 65,
            'endFilePos' => 910,
          ),
        ),
        'docComment' => '/**
 * Elements that should be removed but their children should be retained.
 *
 * @var array<string, true>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowedElements' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'allowedElements',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 78,
            'startFilePos' => 1093,
            'endTokenPos' => 79,
            'endFilePos' => 1094,
          ),
        ),
        'docComment' => '/**
 * Elements that should be retained, with their allowed attributes.
 *
 * @var array<string, array<string, true>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'forcedAttributes' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'forcedAttributes',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 92,
            'startFilePos' => 1275,
            'endTokenPos' => 93,
            'endFilePos' => 1276,
          ),
        ),
        'docComment' => '/**
 * Attributes that should always be added to certain elements.
 *
 * @var array<string, array<string, string>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowedLinkSchemes' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'allowedLinkSchemes',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[\'http\', \'https\', \'mailto\', \'tel\']',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 106,
            'startFilePos' => 1435,
            'endTokenPos' => 117,
            'endFilePos' => 1468,
          ),
        ),
        'docComment' => '/**
 * Links schemes that should be retained, other being dropped.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 75,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowedLinkHosts' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'allowedLinkHosts',
        'modifiers' => 4,
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
                  'name' => 'array',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 64,
            'startTokenPos' => 131,
            'startFilePos' => 1644,
            'endTokenPos' => 131,
            'endFilePos' => 1647,
          ),
        ),
        'docComment' => '/**
 * Links hosts that should be retained (by default, all hosts are allowed).
 *
 * @var list<string>|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowRelativeLinks' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'allowRelativeLinks',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 144,
            'startFilePos' => 1787,
            'endTokenPos' => 144,
            'endFilePos' => 1791,
          ),
        ),
        'docComment' => '/**
 * Should the sanitizer allow relative links (by default, they are dropped).
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 45,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowedMediaSchemes' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'allowedMediaSchemes',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[\'http\', \'https\', \'data\']',
          'attributes' => 
          array (
            'startLine' => 76,
            'endLine' => 76,
            'startTokenPos' => 157,
            'startFilePos' => 1963,
            'endTokenPos' => 165,
            'endFilePos' => 1987,
          ),
        ),
        'docComment' => '/**
 * Image/Audio/Video schemes that should be retained, other being dropped.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 67,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowedMediaHosts' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'allowedMediaHosts',
        'modifiers' => 4,
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
                  'name' => 'array',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 83,
            'endLine' => 83,
            'startTokenPos' => 179,
            'startFilePos' => 2176,
            'endTokenPos' => 179,
            'endFilePos' => 2179,
          ),
        ),
        'docComment' => '/**
 * Image/Audio/Video hosts that should be retained (by default, all hosts are allowed).
 *
 * @var list<string>|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 45,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowRelativeMedias' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'allowRelativeMedias',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 88,
            'endLine' => 88,
            'startTokenPos' => 192,
            'startFilePos' => 2324,
            'endTokenPos' => 192,
            'endFilePos' => 2328,
          ),
        ),
        'docComment' => '/**
 * Should the sanitizer allow relative media URL (by default, they are dropped).
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 88,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 46,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'forceHttpsUrls' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'forceHttpsUrls',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 93,
            'endLine' => 93,
            'startTokenPos' => 205,
            'startFilePos' => 2479,
            'endTokenPos' => 205,
            'endFilePos' => 2483,
          ),
        ),
        'docComment' => '/**
 * Should the URL in the sanitized document be transformed to HTTPS if they are using HTTP.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributeSanitizers' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'attributeSanitizers',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Sanitizers that should be applied to specific attributes in addition to standard sanitization.
 *
 * @var list<AttributeSanitizerInterface>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'maxInputLength' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'name' => 'maxInputLength',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '20000',
          'attributes' => 
          array (
            'startLine' => 102,
            'endLine' => 102,
            'startTokenPos' => 225,
            'startFilePos' => 2733,
            'endTokenPos' => 225,
            'endFilePos' => 2738,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 102,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 41,
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
        'docComment' => NULL,
        'startLine' => 104,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'defaultAction' => 
      array (
        'name' => 'defaultAction',
        'parameters' => 
        array (
          'action' => 
          array (
            'name' => 'action',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerAction',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 116,
            'endLine' => 116,
            'startColumn' => 35,
            'endColumn' => 61,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets the default action for elements which are not otherwise specifically allowed or blocked.
 *
 * Note that a default action of Allow will allow all tags but they will not have any attributes.
 */',
        'startLine' => 116,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowStaticElements' => 
      array (
        'name' => 'allowStaticElements',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows all static elements and attributes from the W3C Sanitizer API standard.
 *
 * All scripts will be removed but the output may still contain other dangerous
 * behaviors like CSS injection (click-jacking), CSS expressions, ...
 */',
        'startLine' => 130,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowSafeElements' => 
      array (
        'name' => 'allowSafeElements',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows "safe" elements and attributes.
 *
 * All scripts will be removed, as well as other dangerous behaviors like CSS injection.
 */',
        'startLine' => 150,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowLinkSchemes' => 
      array (
        'name' => 'allowLinkSchemes',
        'parameters' => 
        array (
          'allowLinkSchemes' => 
          array (
            'name' => 'allowLinkSchemes',
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
            'startLine' => 183,
            'endLine' => 183,
            'startColumn' => 38,
            'endColumn' => 60,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows only a given list of schemes to be used in links href attributes.
 *
 * All other schemes will be dropped.
 *
 * @param list<string> $allowLinkSchemes
 */',
        'startLine' => 183,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowLinkHosts' => 
      array (
        'name' => 'allowLinkHosts',
        'parameters' => 
        array (
          'allowLinkHosts' => 
          array (
            'name' => 'allowLinkHosts',
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
                      'name' => 'array',
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
            'startLine' => 199,
            'endLine' => 199,
            'startColumn' => 36,
            'endColumn' => 57,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows only a given list of hosts to be used in links href attributes.
 *
 * All other hosts will be dropped. By default all hosts are allowed
 * ($allowedLinkHosts = null).
 *
 * @param list<string>|null $allowLinkHosts
 */',
        'startLine' => 199,
        'endLine' => 205,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowRelativeLinks' => 
      array (
        'name' => 'allowRelativeLinks',
        'parameters' => 
        array (
          'allowRelativeLinks' => 
          array (
            'name' => 'allowRelativeLinks',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 210,
                'endLine' => 210,
                'startTokenPos' => 655,
                'startFilePos' => 5798,
                'endTokenPos' => 655,
                'endFilePos' => 5801,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 210,
            'endLine' => 210,
            'startColumn' => 40,
            'endColumn' => 70,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows relative URLs to be used in links href attributes.
 */',
        'startLine' => 210,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowMediaSchemes' => 
      array (
        'name' => 'allowMediaSchemes',
        'parameters' => 
        array (
          'allowMediaSchemes' => 
          array (
            'name' => 'allowMediaSchemes',
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
            'startLine' => 225,
            'endLine' => 225,
            'startColumn' => 39,
            'endColumn' => 62,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows only a given list of schemes to be used in media source attributes (img, audio, video, ...).
 *
 * All other schemes will be dropped.
 *
 * @param list<string> $allowMediaSchemes
 */',
        'startLine' => 225,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowMediaHosts' => 
      array (
        'name' => 'allowMediaHosts',
        'parameters' => 
        array (
          'allowMediaHosts' => 
          array (
            'name' => 'allowMediaHosts',
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
                      'name' => 'array',
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
            'startLine' => 241,
            'endLine' => 241,
            'startColumn' => 37,
            'endColumn' => 59,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows only a given list of hosts to be used in media source attributes (img, audio, video, ...).
 *
 * All other hosts will be dropped. By default all hosts are allowed
 * ($allowMediaHosts = null).
 *
 * @param list<string>|null $allowMediaHosts
 */',
        'startLine' => 241,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowRelativeMedias' => 
      array (
        'name' => 'allowRelativeMedias',
        'parameters' => 
        array (
          'allowRelativeMedias' => 
          array (
            'name' => 'allowRelativeMedias',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 252,
                'endLine' => 252,
                'startTokenPos' => 789,
                'startFilePos' => 7016,
                'endTokenPos' => 789,
                'endFilePos' => 7019,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 252,
            'endLine' => 252,
            'startColumn' => 41,
            'endColumn' => 72,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows relative URLs to be used in media source attributes (img, audio, video, ...).
 */',
        'startLine' => 252,
        'endLine' => 258,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'forceHttpsUrls' => 
      array (
        'name' => 'forceHttpsUrls',
        'parameters' => 
        array (
          'forceHttpsUrls' => 
          array (
            'name' => 'forceHttpsUrls',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 263,
                'endLine' => 263,
                'startTokenPos' => 836,
                'startFilePos' => 7309,
                'endTokenPos' => 836,
                'endFilePos' => 7312,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 36,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Transforms URLs using the HTTP scheme to use the HTTPS scheme instead.
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
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowElement' => 
      array (
        'name' => 'allowElement',
        'parameters' => 
        array (
          'element' => 
          array (
            'name' => 'element',
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
            'startLine' => 282,
            'endLine' => 282,
            'startColumn' => 34,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'allowedAttributes' => 
          array (
            'name' => 'allowedAttributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 282,
                'endLine' => 282,
                'startTokenPos' => 890,
                'startFilePos' => 7964,
                'endTokenPos' => 891,
                'endFilePos' => 7965,
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 282,
            'endLine' => 282,
            'startColumn' => 51,
            'endColumn' => 86,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configures the given element as allowed.
 *
 * Allowed elements are elements the sanitizer should retain from the input.
 *
 * A list of allowed attributes for this element can be passed as a second argument.
 * Passing "*" will allow all standard attributes on this element. By default, no
 * attributes are allowed on the element.
 *
 * @param list<string>|string $allowedAttributes
 */',
        'startLine' => 282,
        'endLine' => 297,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'blockElement' => 
      array (
        'name' => 'blockElement',
        'parameters' => 
        array (
          'element' => 
          array (
            'name' => 'element',
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
            'startLine' => 305,
            'endLine' => 305,
            'startColumn' => 34,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configures the given element as blocked.
 *
 * Blocked elements are elements the sanitizer should remove from the input, but retain
 * their children.
 */',
        'startLine' => 305,
        'endLine' => 315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'dropElement' => 
      array (
        'name' => 'dropElement',
        'parameters' => 
        array (
          'element' => 
          array (
            'name' => 'element',
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
            'startLine' => 327,
            'endLine' => 327,
            'startColumn' => 33,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configures the given element as dropped.
 *
 * Dropped elements are elements the sanitizer should remove from the input, including
 * their children.
 *
 * Note: when using an empty configuration, all unknown elements are dropped
 * automatically. This method let you drop elements that were allowed earlier
 * in the configuration, or explicitly drop some if you changed the default action.
 */',
        'startLine' => 327,
        'endLine' => 335,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'allowAttribute' => 
      array (
        'name' => 'allowAttribute',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
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
            'startLine' => 347,
            'endLine' => 347,
            'startColumn' => 36,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'allowedElements' => 
          array (
            'name' => 'allowedElements',
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 347,
            'endLine' => 347,
            'startColumn' => 55,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configures the given attribute as allowed.
 *
 * Allowed attributes are attributes the sanitizer should retain from the input.
 *
 * A list of allowed elements for this attribute can be passed as a second argument.
 * Passing "*" will allow all currently allowed elements to use this attribute.
 *
 * @param list<string>|string $allowedElements
 */',
        'startLine' => 347,
        'endLine' => 364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'dropAttribute' => 
      array (
        'name' => 'dropAttribute',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
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
            'startLine' => 380,
            'endLine' => 380,
            'startColumn' => 35,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'droppedElements' => 
          array (
            'name' => 'droppedElements',
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 380,
            'endLine' => 380,
            'startColumn' => 54,
            'endColumn' => 82,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configures the given attribute as dropped.
 *
 * Dropped attributes are attributes the sanitizer should remove from the input.
 *
 * A list of elements on which to drop this attribute can be passed as a second argument.
 * Passing "*" will drop this attribute from all currently allowed elements.
 *
 * Note: when using an empty configuration, all unknown attributes are dropped
 * automatically. This method let you drop attributes that were allowed earlier
 * in the configuration.
 *
 * @param list<string>|string $droppedElements
 */',
        'startLine' => 380,
        'endLine' => 392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'forceAttribute' => 
      array (
        'name' => 'forceAttribute',
        'parameters' => 
        array (
          'element' => 
          array (
            'name' => 'element',
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
            'startLine' => 399,
            'endLine' => 399,
            'startColumn' => 36,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
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
            'startLine' => 399,
            'endLine' => 399,
            'startColumn' => 53,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 399,
            'endLine' => 399,
            'startColumn' => 72,
            'endColumn' => 84,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Forcefully set the value of a given attribute on a given element.
 *
 * The attribute will be created on the nodes if it didn\'t exist.
 */',
        'startLine' => 399,
        'endLine' => 405,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'withAttributeSanitizer' => 
      array (
        'name' => 'withAttributeSanitizer',
        'parameters' => 
        array (
          'sanitizer' => 
          array (
            'name' => 'sanitizer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\AttributeSanitizer\\AttributeSanitizerInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 410,
            'endLine' => 410,
            'startColumn' => 44,
            'endColumn' => 81,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Registers a custom attribute sanitizer.
 */',
        'startLine' => 410,
        'endLine' => 416,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'withoutAttributeSanitizer' => 
      array (
        'name' => 'withoutAttributeSanitizer',
        'parameters' => 
        array (
          'sanitizer' => 
          array (
            'name' => 'sanitizer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\AttributeSanitizer\\AttributeSanitizerInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 47,
            'endColumn' => 84,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Unregisters a custom attribute sanitizer.
 */',
        'startLine' => 421,
        'endLine' => 430,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'withMaxInputLength' => 
      array (
        'name' => 'withMaxInputLength',
        'parameters' => 
        array (
          'maxInputLength' => 
          array (
            'name' => 'maxInputLength',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 436,
            'endLine' => 436,
            'startColumn' => 40,
            'endColumn' => 58,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param int $maxInputLength The maximum length of the input string in bytes
 *                            -1 means no limit
 */',
        'startLine' => 436,
        'endLine' => 446,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getMaxInputLength' => 
      array (
        'name' => 'getMaxInputLength',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 448,
        'endLine' => 451,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getDefaultAction' => 
      array (
        'name' => 'getDefaultAction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerAction',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 453,
        'endLine' => 456,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getAllowedElements' => 
      array (
        'name' => 'getAllowedElements',
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
 * @return array<string, array<string, true>>
 */',
        'startLine' => 461,
        'endLine' => 464,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getBlockedElements' => 
      array (
        'name' => 'getBlockedElements',
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
 * @return array<string, true>
 */',
        'startLine' => 469,
        'endLine' => 472,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getDroppedElements' => 
      array (
        'name' => 'getDroppedElements',
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
 * @return array<string, true>
 */',
        'startLine' => 477,
        'endLine' => 480,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getForcedAttributes' => 
      array (
        'name' => 'getForcedAttributes',
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
 * @return array<string, array<string, string>>
 */',
        'startLine' => 485,
        'endLine' => 488,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getAllowedLinkSchemes' => 
      array (
        'name' => 'getAllowedLinkSchemes',
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
 * @return list<string>
 */',
        'startLine' => 493,
        'endLine' => 496,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getAllowedLinkHosts' => 
      array (
        'name' => 'getAllowedLinkHosts',
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
                  'name' => 'array',
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
 * @return list<string>|null
 */',
        'startLine' => 501,
        'endLine' => 504,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getAllowRelativeLinks' => 
      array (
        'name' => 'getAllowRelativeLinks',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 506,
        'endLine' => 509,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getAllowedMediaSchemes' => 
      array (
        'name' => 'getAllowedMediaSchemes',
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
 * @return list<string>
 */',
        'startLine' => 514,
        'endLine' => 517,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getAllowedMediaHosts' => 
      array (
        'name' => 'getAllowedMediaHosts',
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
                  'name' => 'array',
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
 * @return list<string>|null
 */',
        'startLine' => 522,
        'endLine' => 525,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getAllowRelativeMedias' => 
      array (
        'name' => 'getAllowRelativeMedias',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 527,
        'endLine' => 530,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getForceHttpsUrls' => 
      array (
        'name' => 'getForceHttpsUrls',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 532,
        'endLine' => 535,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'aliasName' => NULL,
      ),
      'getAttributeSanitizers' => 
      array (
        'name' => 'getAttributeSanitizers',
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
 * @return list<AttributeSanitizerInterface>
 */',
        'startLine' => 540,
        'endLine' => 543,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
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