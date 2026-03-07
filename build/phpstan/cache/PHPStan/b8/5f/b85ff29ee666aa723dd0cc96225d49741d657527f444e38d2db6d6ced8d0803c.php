<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/forms/src/Components/MarkdownEditor.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Filament\Forms\Components\MarkdownEditor
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-61774ecdd80271d18fd689d805e25cb1bdca6601e9f708e7c7fce954876981ff-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/forms/src/Components/MarkdownEditor.php',
      ),
    ),
    'namespace' => 'Filament\\Forms\\Components',
    'name' => 'Filament\\Forms\\Components\\MarkdownEditor',
    'shortName' => 'MarkdownEditor',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 70,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Filament\\Forms\\Components\\Field',
    'implementsClassNames' => 
    array (
      0 => 'Filament\\Forms\\Components\\Contracts\\CanBeLengthConstrained',
    ),
    'traitClassNames' => 
    array (
      0 => 'Filament\\Support\\Concerns\\CanConfigureCommonMark',
      1 => 'Filament\\Forms\\Components\\Concerns\\CanBeLengthConstrained',
      2 => 'Filament\\Forms\\Components\\Concerns\\HasFileAttachments',
      3 => 'Filament\\Forms\\Components\\Concerns\\HasMaxHeight',
      4 => 'Filament\\Forms\\Components\\Concerns\\HasMinHeight',
      5 => 'Filament\\Forms\\Components\\Concerns\\HasPlaceholder',
      6 => 'Filament\\Forms\\Components\\Concerns\\InteractsWithToolbarButtons',
      7 => 'Filament\\Support\\Concerns\\HasExtraAlpineAttributes',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'view' => 
      array (
        'declaringClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'implementingClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'name' => 'view',
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
          'code' => '\'filament-forms::components.markdown-editor\'',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 91,
            'startFilePos' => 625,
            'endTokenPos' => 91,
            'endFilePos' => 668,
          ),
        ),
        'docComment' => '/**
 * @var view-string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 74,
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
      'getDefaultToolbarButtons' => 
      array (
        'name' => 'getDefaultToolbarButtons',
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
 * @return array<string | array<string>>
 */',
        'startLine' => 29,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Forms\\Components',
        'declaringClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'implementingClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'currentClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'aliasName' => NULL,
      ),
      'getFileAttachmentsDiskName' => 
      array (
        'name' => 'getFileAttachmentsDiskName',
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
        'docComment' => NULL,
        'startLine' => 43,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Forms\\Components',
        'declaringClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'implementingClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'currentClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'aliasName' => NULL,
      ),
      'fileAttachmentsVisibility' => 
      array (
        'name' => 'fileAttachmentsVisibility',
        'parameters' => 
        array (
          'visibility' => 
          array (
            'name' => 'visibility',
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
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Closure',
                      'isIdentifier' => false,
                    ),
                  ),
                  2 => 
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
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 47,
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
        'docComment' => NULL,
        'startLine' => 56,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Forms\\Components',
        'declaringClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'implementingClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'currentClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'aliasName' => NULL,
      ),
      'getFileAttachmentsVisibility' => 
      array (
        'name' => 'getFileAttachmentsVisibility',
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
        'docComment' => NULL,
        'startLine' => 61,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Forms\\Components',
        'declaringClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'implementingClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'currentClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'aliasName' => NULL,
      ),
      'hasFileAttachmentsByDefault' => 
      array (
        'name' => 'hasFileAttachmentsByDefault',
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
        'startLine' => 66,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Forms\\Components',
        'declaringClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'implementingClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
        'currentClassName' => 'Filament\\Forms\\Components\\MarkdownEditor',
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