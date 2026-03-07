<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Validation/Validator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Validation\Validator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6f2363845c356d949ea786eb6b80bacc228227b34f4470b9205e0d7fa6fce87a-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Validation\\Validator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Validation/Validator.php',
      ),
    ),
    'namespace' => 'Illuminate\\Validation',
    'name' => 'Illuminate\\Validation\\Validator',
    'shortName' => 'Validator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 1691,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Validation\\Validator',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Validation\\Concerns\\FormatsMessages',
      1 => 'Illuminate\\Validation\\Concerns\\ValidatesAttributes',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'translator' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'translator',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The Translator implementation.
 *
 * @var \\Illuminate\\Contracts\\Translation\\Translator
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'container' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'container',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The container instance.
 *
 * @var \\Illuminate\\Contracts\\Container\\Container
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'presenceVerifier' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'presenceVerifier',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The Presence Verifier implementation.
 *
 * @var \\Illuminate\\Validation\\PresenceVerifierInterface
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'failedRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'failedRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 152,
            'startFilePos' => 1436,
            'endTokenPos' => 153,
            'endFilePos' => 1437,
          ),
        ),
        'docComment' => '/**
 * The failed validation rules.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'excludeAttributes' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'excludeAttributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 164,
            'startFilePos' => 1584,
            'endTokenPos' => 165,
            'endFilePos' => 1585,
          ),
        ),
        'docComment' => '/**
 * Attributes that should be excluded from the validated data.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'messages' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'messages',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The message bag instance.
 *
 * @var \\Illuminate\\Support\\MessageBag
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'data' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'data',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The data under validation.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'initialRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'initialRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The initial rules provided.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'rules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'rules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The rules to be applied to the data.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'currentRule' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'currentRule',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The current rule that is validating.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'implicitAttributes' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'implicitAttributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 104,
            'endLine' => 104,
            'startTokenPos' => 211,
            'startFilePos' => 2288,
            'endTokenPos' => 212,
            'endFilePos' => 2289,
          ),
        ),
        'docComment' => '/**
 * The array of wildcard attributes with their asterisks expanded.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'implicitAttributesFormatter' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'implicitAttributesFormatter',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that should be used to format the attribute.
 *
 * @var callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 111,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'distinctValues' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'distinctValues',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 118,
            'endLine' => 118,
            'startTokenPos' => 230,
            'startFilePos' => 2573,
            'endTokenPos' => 231,
            'endFilePos' => 2574,
          ),
        ),
        'docComment' => '/**
 * The cached data for the "distinct" rule.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 118,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'after' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'after',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 125,
            'endLine' => 125,
            'startTokenPos' => 242,
            'startFilePos' => 2690,
            'endTokenPos' => 243,
            'endFilePos' => 2691,
          ),
        ),
        'docComment' => '/**
 * All of the registered "after" callbacks.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'customMessages' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'customMessages',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 132,
            'endLine' => 132,
            'startTokenPos' => 254,
            'startFilePos' => 2808,
            'endTokenPos' => 255,
            'endFilePos' => 2809,
          ),
        ),
        'docComment' => '/**
 * The array of custom error messages.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 132,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fallbackMessages' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'fallbackMessages',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 139,
            'endLine' => 139,
            'startTokenPos' => 266,
            'startFilePos' => 2930,
            'endTokenPos' => 267,
            'endFilePos' => 2931,
          ),
        ),
        'docComment' => '/**
 * The array of fallback error messages.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 139,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'customAttributes' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'customAttributes',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 146,
            'endLine' => 146,
            'startTokenPos' => 278,
            'startFilePos' => 3051,
            'endTokenPos' => 279,
            'endFilePos' => 3052,
          ),
        ),
        'docComment' => '/**
 * The array of custom attribute names.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 146,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'customValues' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'customValues',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 153,
            'endLine' => 153,
            'startTokenPos' => 290,
            'startFilePos' => 3171,
            'endTokenPos' => 291,
            'endFilePos' => 3172,
          ),
        ),
        'docComment' => '/**
 * The array of custom displayable values.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 153,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'stopOnFirstFailure' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'stopOnFirstFailure',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 160,
            'endLine' => 160,
            'startTokenPos' => 302,
            'startFilePos' => 3325,
            'endTokenPos' => 302,
            'endFilePos' => 3329,
          ),
        ),
        'docComment' => '/**
 * Indicates if the validator should stop on the first rule failure.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 160,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'excludeUnvalidatedArrayKeys' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'excludeUnvalidatedArrayKeys',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 167,
            'endLine' => 167,
            'startTokenPos' => 313,
            'startFilePos' => 3520,
            'endTokenPos' => 313,
            'endFilePos' => 3524,
          ),
        ),
        'docComment' => '/**
 * Indicates that unvalidated array keys should be excluded, even if the parent array was validated.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 167,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 48,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'extensions' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'extensions',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 174,
            'endLine' => 174,
            'startTokenPos' => 324,
            'startFilePos' => 3641,
            'endTokenPos' => 325,
            'endFilePos' => 3642,
          ),
        ),
        'docComment' => '/**
 * All of the custom validator extensions.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 174,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'replacers' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'replacers',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 181,
            'endLine' => 181,
            'startTokenPos' => 336,
            'startFilePos' => 3757,
            'endTokenPos' => 337,
            'endFilePos' => 3758,
          ),
        ),
        'docComment' => '/**
 * All of the custom replacer extensions.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 181,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fileRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'fileRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Between\', \'Dimensions\', \'Extensions\', \'File\', \'Image\', \'Max\', \'Mimes\', \'Mimetypes\', \'Min\', \'Size\']',
          'attributes' => 
          array (
            'startLine' => 188,
            'endLine' => 199,
            'startTokenPos' => 348,
            'startFilePos' => 3891,
            'endTokenPos' => 380,
            'endFilePos' => 4077,
          ),
        ),
        'docComment' => '/**
 * The validation rules that may be applied to files.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 188,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'implicitRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'implicitRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Accepted\', \'AcceptedIf\', \'Declined\', \'DeclinedIf\', \'Filled\', \'Missing\', \'MissingIf\', \'MissingUnless\', \'MissingWith\', \'MissingWithAll\', \'Present\', \'PresentIf\', \'PresentUnless\', \'PresentWith\', \'PresentWithAll\', \'Required\', \'RequiredIf\', \'RequiredIfAccepted\', \'RequiredIfDeclined\', \'RequiredUnless\', \'RequiredWith\', \'RequiredWithAll\', \'RequiredWithout\', \'RequiredWithoutAll\']',
          'attributes' => 
          array (
            'startLine' => 206,
            'endLine' => 231,
            'startTokenPos' => 391,
            'startFilePos' => 4218,
            'endTokenPos' => 465,
            'endFilePos' => 4790,
          ),
        ),
        'docComment' => '/**
 * The validation rules that imply the field is required.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 206,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'dependentRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'dependentRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'After\', \'AfterOrEqual\', \'Before\', \'BeforeOrEqual\', \'Confirmed\', \'Different\', \'ExcludeIf\', \'ExcludeUnless\', \'ExcludeWith\', \'ExcludeWithout\', \'Gt\', \'Gte\', \'Lt\', \'Lte\', \'AcceptedIf\', \'DeclinedIf\', \'RequiredIf\', \'RequiredIfAccepted\', \'RequiredIfDeclined\', \'RequiredUnless\', \'RequiredWith\', \'RequiredWithAll\', \'RequiredWithout\', \'RequiredWithoutAll\', \'PresentIf\', \'PresentUnless\', \'PresentWith\', \'PresentWithAll\', \'Prohibited\', \'ProhibitedIf\', \'ProhibitedIfAccepted\', \'ProhibitedIfDeclined\', \'ProhibitedUnless\', \'Prohibits\', \'MissingIf\', \'MissingUnless\', \'MissingWith\', \'MissingWithAll\', \'Same\', \'Unique\']',
          'attributes' => 
          array (
            'startLine' => 238,
            'endLine' => 279,
            'startTokenPos' => 476,
            'startFilePos' => 4942,
            'endTokenPos' => 598,
            'endFilePos' => 5870,
          ),
        ),
        'docComment' => '/**
 * The validation rules which depend on other fields as parameters.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 238,
        'endLine' => 279,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'excludeRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'excludeRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Exclude\', \'ExcludeIf\', \'ExcludeUnless\', \'ExcludeWith\', \'ExcludeWithout\']',
          'attributes' => 
          array (
            'startLine' => 286,
            'endLine' => 286,
            'startTokenPos' => 609,
            'startFilePos' => 6007,
            'endTokenPos' => 623,
            'endFilePos' => 6080,
          ),
        ),
        'docComment' => '/**
 * The validation rules that can exclude an attribute.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 286,
        'endLine' => 286,
        'startColumn' => 5,
        'endColumn' => 105,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'sizeRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'sizeRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Size\', \'Between\', \'Min\', \'Max\', \'Gt\', \'Lt\', \'Gte\', \'Lte\']',
          'attributes' => 
          array (
            'startLine' => 293,
            'endLine' => 293,
            'startTokenPos' => 634,
            'startFilePos' => 6197,
            'endTokenPos' => 657,
            'endFilePos' => 6255,
          ),
        ),
        'docComment' => '/**
 * The size related validation rules.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 293,
        'endLine' => 293,
        'startColumn' => 5,
        'endColumn' => 87,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'numericRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'numericRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Numeric\', \'Integer\', \'Decimal\']',
          'attributes' => 
          array (
            'startLine' => 300,
            'endLine' => 300,
            'startTokenPos' => 668,
            'startFilePos' => 6378,
            'endTokenPos' => 676,
            'endFilePos' => 6410,
          ),
        ),
        'docComment' => '/**
 * The numeric related validation rules.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 300,
        'endLine' => 300,
        'startColumn' => 5,
        'endColumn' => 64,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultNumericRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'defaultNumericRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Numeric\', \'Integer\', \'Decimal\']',
          'attributes' => 
          array (
            'startLine' => 307,
            'endLine' => 307,
            'startTokenPos' => 687,
            'startFilePos' => 6548,
            'endTokenPos' => 695,
            'endFilePos' => 6580,
          ),
        ),
        'docComment' => '/**
 * The default numeric related validation rules.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 307,
        'endLine' => 307,
        'startColumn' => 5,
        'endColumn' => 71,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'placeholderHash' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'placeholderHash',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The current random hash for the validator.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 314,
        'endLine' => 314,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'exception' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'exception',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Illuminate\\Validation\\ValidationException::class',
          'attributes' => 
          array (
            'startLine' => 321,
            'endLine' => 321,
            'startTokenPos' => 715,
            'startFilePos' => 6879,
            'endTokenPos' => 717,
            'endFilePos' => 6904,
          ),
        ),
        'docComment' => '/**
 * The exception to throw upon failure.
 *
 * @var class-string<\\Illuminate\\Validation\\ValidationException>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 321,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 54,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'ensureExponentWithinAllowedRangeUsing' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'name' => 'ensureExponentWithinAllowedRangeUsing',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom callback to determine if an exponent is within allowed range.
 *
 * @var callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 328,
        'endLine' => 328,
        'startColumn' => 5,
        'endColumn' => 53,
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
          'translator' => 
          array (
            'name' => 'translator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Translation\\Translator',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 341,
            'endLine' => 341,
            'startColumn' => 9,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
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
            'startLine' => 342,
            'endLine' => 342,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'rules' => 
          array (
            'name' => 'rules',
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
            'startLine' => 343,
            'endLine' => 343,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'messages' => 
          array (
            'name' => 'messages',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 344,
                'endLine' => 344,
                'startTokenPos' => 757,
                'startFilePos' => 7505,
                'endTokenPos' => 758,
                'endFilePos' => 7506,
              ),
            ),
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
            'startLine' => 344,
            'endLine' => 344,
            'startColumn' => 9,
            'endColumn' => 28,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 345,
                'endLine' => 345,
                'startTokenPos' => 767,
                'startFilePos' => 7537,
                'endTokenPos' => 768,
                'endFilePos' => 7538,
              ),
            ),
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
            'startLine' => 345,
            'endLine' => 345,
            'startColumn' => 9,
            'endColumn' => 30,
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
 * Create a new Validator instance.
 *
 * @param  \\Illuminate\\Contracts\\Translation\\Translator  $translator
 * @param  array  $data
 * @param  array  $rules
 * @param  array  $messages
 * @param  array  $attributes
 * @return void
 */',
        'startLine' => 340,
        'endLine' => 358,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'parseData' => 
      array (
        'name' => 'parseData',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
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
            'startLine' => 366,
            'endLine' => 366,
            'startColumn' => 31,
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
 * Parse the data array, converting dots and asterisks.
 *
 * @param  array  $data
 * @return array
 */',
        'startLine' => 366,
        'endLine' => 385,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'replacePlaceholders' => 
      array (
        'name' => 'replacePlaceholders',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 393,
            'endLine' => 393,
            'startColumn' => 44,
            'endColumn' => 48,
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
 * Replace the placeholders used in data keys.
 *
 * @param  array  $data
 * @return array
 */',
        'startLine' => 393,
        'endLine' => 404,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'replacePlaceholderInString' => 
      array (
        'name' => 'replacePlaceholderInString',
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
            'startLine' => 412,
            'endLine' => 412,
            'startColumn' => 51,
            'endColumn' => 63,
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
 * Replace the placeholders in the given string.
 *
 * @param  string  $value
 * @return string
 */',
        'startLine' => 412,
        'endLine' => 419,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'replaceDotPlaceholderInParameters' => 
      array (
        'name' => 'replaceDotPlaceholderInParameters',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
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
            'startLine' => 427,
            'endLine' => 427,
            'startColumn' => 58,
            'endColumn' => 74,
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
 * Replace each field parameter dot placeholder with dot.
 *
 * @param  array  $parameters
 * @return array
 */',
        'startLine' => 427,
        'endLine' => 432,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'after' => 
      array (
        'name' => 'after',
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
            'startLine' => 440,
            'endLine' => 440,
            'startColumn' => 27,
            'endColumn' => 35,
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
 * Add an after validation callback.
 *
 * @param  callable|array|string  $callback
 * @return $this
 */',
        'startLine' => 440,
        'endLine' => 453,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'passes' => 
      array (
        'name' => 'passes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the data passes the validation rules.
 *
 * @return bool
 */',
        'startLine' => 460,
        'endLine' => 507,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'fails' => 
      array (
        'name' => 'fails',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the data fails the validation rules.
 *
 * @return bool
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
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'shouldBeExcluded' => 
      array (
        'name' => 'shouldBeExcluded',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 525,
            'endLine' => 525,
            'startColumn' => 41,
            'endColumn' => 50,
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
 * Determine if the attribute should be excluded.
 *
 * @param  string  $attribute
 * @return bool
 */',
        'startLine' => 525,
        'endLine' => 535,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'removeAttribute' => 
      array (
        'name' => 'removeAttribute',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 543,
            'endLine' => 543,
            'startColumn' => 40,
            'endColumn' => 49,
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
 * Remove the given attribute.
 *
 * @param  string  $attribute
 * @return void
 */',
        'startLine' => 543,
        'endLine' => 547,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'validate' => 
      array (
        'name' => 'validate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run the validator\'s rules against its data.
 *
 * @return array
 *
 * @throws \\Illuminate\\Validation\\ValidationException
 */',
        'startLine' => 556,
        'endLine' => 561,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'validateWithBag' => 
      array (
        'name' => 'validateWithBag',
        'parameters' => 
        array (
          'errorBag' => 
          array (
            'name' => 'errorBag',
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
            'startLine' => 571,
            'endLine' => 571,
            'startColumn' => 37,
            'endColumn' => 52,
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
 * Run the validator\'s rules against its data.
 *
 * @param  string  $errorBag
 * @return array
 *
 * @throws \\Illuminate\\Validation\\ValidationException
 */',
        'startLine' => 571,
        'endLine' => 580,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'safe' => 
      array (
        'name' => 'safe',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 588,
                'endLine' => 588,
                'startTokenPos' => 1791,
                'startFilePos' => 13842,
                'endTokenPos' => 1791,
                'endFilePos' => 13845,
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
            'startLine' => 588,
            'endLine' => 588,
            'startColumn' => 26,
            'endColumn' => 44,
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
 * Get a validated input container for the validated input.
 *
 * @param  array|null  $keys
 * @return \\Illuminate\\Support\\ValidatedInput|array
 */',
        'startLine' => 588,
        'endLine' => 593,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'validated' => 
      array (
        'name' => 'validated',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the attributes and values that were validated.
 *
 * @return array
 *
 * @throws \\Illuminate\\Validation\\ValidationException
 */',
        'startLine' => 602,
        'endLine' => 630,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'validateAttribute' => 
      array (
        'name' => 'validateAttribute',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 639,
            'endLine' => 639,
            'startColumn' => 42,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 639,
            'endLine' => 639,
            'startColumn' => 54,
            'endColumn' => 58,
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
 * Validate a given attribute against a rule.
 *
 * @param  string  $attribute
 * @param  string  $rule
 * @return void
 */',
        'startLine' => 639,
        'endLine' => 689,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'dependsOnOtherFields' => 
      array (
        'name' => 'dependsOnOtherFields',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 697,
            'endLine' => 697,
            'startColumn' => 45,
            'endColumn' => 49,
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
 * Determine if the given rule depends on other fields.
 *
 * @param  string  $rule
 * @return bool
 */',
        'startLine' => 697,
        'endLine' => 700,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getExplicitKeys' => 
      array (
        'name' => 'getExplicitKeys',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 710,
            'endLine' => 710,
            'startColumn' => 40,
            'endColumn' => 49,
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
 * Get the explicit keys from an attribute flattened with dot notation.
 *
 * E.g. \'foo.1.bar.spark.baz\' -> [1, \'spark\'] for \'foo.*.bar.*.baz\'
 *
 * @param  string  $attribute
 * @return array
 */',
        'startLine' => 710,
        'endLine' => 721,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getPrimaryAttribute' => 
      array (
        'name' => 'getPrimaryAttribute',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 731,
            'endLine' => 731,
            'startColumn' => 44,
            'endColumn' => 53,
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
 * Get the primary attribute name.
 *
 * For example, if "name.0" is given, "name.*" will be returned.
 *
 * @param  string  $attribute
 * @return string
 */',
        'startLine' => 731,
        'endLine' => 740,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'replaceDotInParameters' => 
      array (
        'name' => 'replaceDotInParameters',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
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
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 47,
            'endColumn' => 63,
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
 * Replace each field parameter which has an escaped dot with the dot placeholder.
 *
 * @param  array  $parameters
 * @return array
 */',
        'startLine' => 748,
        'endLine' => 753,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'replaceAsterisksInParameters' => 
      array (
        'name' => 'replaceAsterisksInParameters',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
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
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 53,
            'endColumn' => 69,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'keys' => 
          array (
            'name' => 'keys',
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
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 72,
            'endColumn' => 82,
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
 * Replace each field parameter which has asterisks with the given keys.
 *
 * @param  array  $parameters
 * @param  array  $keys
 * @return array
 */',
        'startLine' => 762,
        'endLine' => 767,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'isValidatable' => 
      array (
        'name' => 'isValidatable',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 777,
            'endLine' => 777,
            'startColumn' => 38,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 777,
            'endLine' => 777,
            'startColumn' => 45,
            'endColumn' => 54,
            'parameterIndex' => 1,
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
            'startLine' => 777,
            'endLine' => 777,
            'startColumn' => 57,
            'endColumn' => 62,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the attribute is validatable.
 *
 * @param  object|string  $rule
 * @param  string  $attribute
 * @param  mixed  $value
 * @return bool
 */',
        'startLine' => 777,
        'endLine' => 787,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'presentOrRuleIsImplicit' => 
      array (
        'name' => 'presentOrRuleIsImplicit',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 797,
            'endLine' => 797,
            'startColumn' => 48,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 797,
            'endLine' => 797,
            'startColumn' => 55,
            'endColumn' => 64,
            'parameterIndex' => 1,
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
            'startLine' => 797,
            'endLine' => 797,
            'startColumn' => 67,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the field is present, or the rule implies required.
 *
 * @param  object|string  $rule
 * @param  string  $attribute
 * @param  mixed  $value
 * @return bool
 */',
        'startLine' => 797,
        'endLine' => 805,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'isImplicit' => 
      array (
        'name' => 'isImplicit',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 813,
            'endLine' => 813,
            'startColumn' => 35,
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
 * Determine if a given rule implies the attribute is required.
 *
 * @param  object|string  $rule
 * @return bool
 */',
        'startLine' => 813,
        'endLine' => 817,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'passesOptionalCheck' => 
      array (
        'name' => 'passesOptionalCheck',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 825,
            'endLine' => 825,
            'startColumn' => 44,
            'endColumn' => 53,
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
 * Determine if the attribute passes any optional check.
 *
 * @param  string  $attribute
 * @return bool
 */',
        'startLine' => 825,
        'endLine' => 835,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'isNotNullIfMarkedAsNullable' => 
      array (
        'name' => 'isNotNullIfMarkedAsNullable',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 844,
            'endLine' => 844,
            'startColumn' => 52,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 844,
            'endLine' => 844,
            'startColumn' => 59,
            'endColumn' => 68,
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
 * Determine if the attribute fails the nullable check.
 *
 * @param  string  $rule
 * @param  string  $attribute
 * @return bool
 */',
        'startLine' => 844,
        'endLine' => 851,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'hasNotFailedPreviousRuleIfPresenceRule' => 
      array (
        'name' => 'hasNotFailedPreviousRuleIfPresenceRule',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 862,
            'endLine' => 862,
            'startColumn' => 63,
            'endColumn' => 67,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 862,
            'endLine' => 862,
            'startColumn' => 70,
            'endColumn' => 79,
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
 * Determine if it\'s a necessary presence validation.
 *
 * This is to avoid possible database type comparison errors.
 *
 * @param  string  $rule
 * @param  string  $attribute
 * @return bool
 */',
        'startLine' => 862,
        'endLine' => 865,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'validateUsingCustomRule' => 
      array (
        'name' => 'validateUsingCustomRule',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 875,
            'endLine' => 875,
            'startColumn' => 48,
            'endColumn' => 57,
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
            'startLine' => 875,
            'endLine' => 875,
            'startColumn' => 60,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 875,
            'endLine' => 875,
            'startColumn' => 68,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Validate an attribute using a custom rule object.
 *
 * @param  string  $attribute
 * @param  mixed  $value
 * @param  \\Illuminate\\Contracts\\Validation\\Rule  $rule
 * @return void
 */',
        'startLine' => 875,
        'endLine' => 921,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'shouldStopValidating' => 
      array (
        'name' => 'shouldStopValidating',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 929,
            'endLine' => 929,
            'startColumn' => 45,
            'endColumn' => 54,
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
 * Check if we should stop further validations on a given attribute.
 *
 * @param  string  $attribute
 * @return bool
 */',
        'startLine' => 929,
        'endLine' => 948,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addFailure' => 
      array (
        'name' => 'addFailure',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 958,
            'endLine' => 958,
            'startColumn' => 32,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 958,
            'endLine' => 958,
            'startColumn' => 44,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 958,
                'endLine' => 958,
                'startTokenPos' => 3656,
                'startFilePos' => 25611,
                'endTokenPos' => 3657,
                'endFilePos' => 25612,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 958,
            'endLine' => 958,
            'startColumn' => 51,
            'endColumn' => 66,
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
 * Add a failed rule and error message to the collection.
 *
 * @param  string  $attribute
 * @param  string  $rule
 * @param  array  $parameters
 * @return void
 */',
        'startLine' => 958,
        'endLine' => 981,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'excludeAttribute' => 
      array (
        'name' => 'excludeAttribute',
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
            'startLine' => 989,
            'endLine' => 989,
            'startColumn' => 41,
            'endColumn' => 57,
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
 * Add the given attribute to the list of excluded attributes.
 *
 * @param  string  $attribute
 * @return void
 */',
        'startLine' => 989,
        'endLine' => 994,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'valid' => 
      array (
        'name' => 'valid',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the data which was valid.
 *
 * @return array
 */',
        'startLine' => 1001,
        'endLine' => 1010,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'invalid' => 
      array (
        'name' => 'invalid',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the data which was invalid.
 *
 * @return array
 */',
        'startLine' => 1017,
        'endLine' => 1036,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'attributesThatHaveMessages' => 
      array (
        'name' => 'attributesThatHaveMessages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate an array of all attributes that have messages.
 *
 * @return array
 */',
        'startLine' => 1043,
        'endLine' => 1048,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'failed' => 
      array (
        'name' => 'failed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the failed validation rules.
 *
 * @return array
 */',
        'startLine' => 1055,
        'endLine' => 1058,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'messages' => 
      array (
        'name' => 'messages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the message container for the validator.
 *
 * @return \\Illuminate\\Support\\MessageBag
 */',
        'startLine' => 1065,
        'endLine' => 1072,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'errors' => 
      array (
        'name' => 'errors',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An alternative more semantic shortcut to the message container.
 *
 * @return \\Illuminate\\Support\\MessageBag
 */',
        'startLine' => 1079,
        'endLine' => 1082,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getMessageBag' => 
      array (
        'name' => 'getMessageBag',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the messages for the instance.
 *
 * @return \\Illuminate\\Support\\MessageBag
 */',
        'startLine' => 1089,
        'endLine' => 1092,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'hasRule' => 
      array (
        'name' => 'hasRule',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1101,
            'endLine' => 1101,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rules' => 
          array (
            'name' => 'rules',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1101,
            'endLine' => 1101,
            'startColumn' => 41,
            'endColumn' => 46,
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
 * Determine if the given attribute has a rule in the given set.
 *
 * @param  string  $attribute
 * @param  string|array  $rules
 * @return bool
 */',
        'startLine' => 1101,
        'endLine' => 1104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getRule' => 
      array (
        'name' => 'getRule',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1113,
            'endLine' => 1113,
            'startColumn' => 32,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rules' => 
          array (
            'name' => 'rules',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1113,
            'endLine' => 1113,
            'startColumn' => 44,
            'endColumn' => 49,
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
 * Get a rule and its parameters for a given attribute.
 *
 * @param  string  $attribute
 * @param  string|array  $rules
 * @return array|null
 */',
        'startLine' => 1113,
        'endLine' => 1128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'attributes' => 
      array (
        'name' => 'attributes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the data under validation.
 *
 * @return array
 */',
        'startLine' => 1135,
        'endLine' => 1138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getData' => 
      array (
        'name' => 'getData',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the data under validation.
 *
 * @return array
 */',
        'startLine' => 1145,
        'endLine' => 1148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setData' => 
      array (
        'name' => 'setData',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
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
            'startLine' => 1156,
            'endLine' => 1156,
            'startColumn' => 29,
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
 * Set the data under validation.
 *
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 1156,
        'endLine' => 1163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getValue' => 
      array (
        'name' => 'getValue',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1171,
            'endLine' => 1171,
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
 * Get the value of a given attribute.
 *
 * @param  string  $attribute
 * @return mixed
 */',
        'startLine' => 1171,
        'endLine' => 1174,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setValue' => 
      array (
        'name' => 'setValue',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1183,
            'endLine' => 1183,
            'startColumn' => 30,
            'endColumn' => 39,
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
            'startLine' => 1183,
            'endLine' => 1183,
            'startColumn' => 42,
            'endColumn' => 47,
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
 * Set the value of a given attribute.
 *
 * @param  string  $attribute
 * @param  mixed  $value
 * @return void
 */',
        'startLine' => 1183,
        'endLine' => 1186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getRules' => 
      array (
        'name' => 'getRules',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the validation rules.
 *
 * @return array
 */',
        'startLine' => 1193,
        'endLine' => 1196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getRulesWithoutPlaceholders' => 
      array (
        'name' => 'getRulesWithoutPlaceholders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the validation rules with key placeholders removed.
 *
 * @return array
 */',
        'startLine' => 1203,
        'endLine' => 1210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setRules' => 
      array (
        'name' => 'setRules',
        'parameters' => 
        array (
          'rules' => 
          array (
            'name' => 'rules',
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
            'startLine' => 1218,
            'endLine' => 1218,
            'startColumn' => 30,
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
 * Set the validation rules.
 *
 * @param  array  $rules
 * @return $this
 */',
        'startLine' => 1218,
        'endLine' => 1231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addRules' => 
      array (
        'name' => 'addRules',
        'parameters' => 
        array (
          'rules' => 
          array (
            'name' => 'rules',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1239,
            'endLine' => 1239,
            'startColumn' => 30,
            'endColumn' => 35,
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
 * Parse the given rules and merge them into current rules.
 *
 * @param  array  $rules
 * @return void
 */',
        'startLine' => 1239,
        'endLine' => 1254,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'sometimes' => 
      array (
        'name' => 'sometimes',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1264,
            'endLine' => 1264,
            'startColumn' => 31,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rules' => 
          array (
            'name' => 'rules',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1264,
            'endLine' => 1264,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1264,
            'endLine' => 1264,
            'startColumn' => 51,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add conditions to a given field based on a Closure.
 *
 * @param  string|array  $attribute
 * @param  string|array  $rules
 * @param  callable  $callback
 * @return $this
 */',
        'startLine' => 1264,
        'endLine' => 1281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'dataForSometimesIteration' => 
      array (
        'name' => 'dataForSometimesIteration',
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
            'startLine' => 1289,
            'endLine' => 1289,
            'startColumn' => 48,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'removeLastSegmentOfAttribute' => 
          array (
            'name' => 'removeLastSegmentOfAttribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1289,
            'endLine' => 1289,
            'startColumn' => 67,
            'endColumn' => 95,
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
 * Get the data that should be injected into the iteration of a wildcard "sometimes" callback.
 *
 * @param  string  $attribute
 * @return \\Illuminate\\Support\\Fluent|array|mixed
 */',
        'startLine' => 1289,
        'endLine' => 1300,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'stopOnFirstFailure' => 
      array (
        'name' => 'stopOnFirstFailure',
        'parameters' => 
        array (
          'stopOnFirstFailure' => 
          array (
            'name' => 'stopOnFirstFailure',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1308,
                'endLine' => 1308,
                'startTokenPos' => 5095,
                'startFilePos' => 34345,
                'endTokenPos' => 5095,
                'endFilePos' => 34348,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1308,
            'endLine' => 1308,
            'startColumn' => 40,
            'endColumn' => 65,
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
 * Instruct the validator to stop validating after the first rule failure.
 *
 * @param  bool  $stopOnFirstFailure
 * @return $this
 */',
        'startLine' => 1308,
        'endLine' => 1313,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addExtensions' => 
      array (
        'name' => 'addExtensions',
        'parameters' => 
        array (
          'extensions' => 
          array (
            'name' => 'extensions',
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
            'startLine' => 1321,
            'endLine' => 1321,
            'startColumn' => 35,
            'endColumn' => 51,
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
 * Register an array of custom validator extensions.
 *
 * @param  array  $extensions
 * @return void
 */',
        'startLine' => 1321,
        'endLine' => 1330,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addImplicitExtensions' => 
      array (
        'name' => 'addImplicitExtensions',
        'parameters' => 
        array (
          'extensions' => 
          array (
            'name' => 'extensions',
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
            'startLine' => 1338,
            'endLine' => 1338,
            'startColumn' => 43,
            'endColumn' => 59,
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
 * Register an array of custom implicit validator extensions.
 *
 * @param  array  $extensions
 * @return void
 */',
        'startLine' => 1338,
        'endLine' => 1345,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addDependentExtensions' => 
      array (
        'name' => 'addDependentExtensions',
        'parameters' => 
        array (
          'extensions' => 
          array (
            'name' => 'extensions',
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
            'startLine' => 1353,
            'endLine' => 1353,
            'startColumn' => 44,
            'endColumn' => 60,
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
 * Register an array of custom dependent validator extensions.
 *
 * @param  array  $extensions
 * @return void
 */',
        'startLine' => 1353,
        'endLine' => 1360,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addExtension' => 
      array (
        'name' => 'addExtension',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1369,
            'endLine' => 1369,
            'startColumn' => 34,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'extension' => 
          array (
            'name' => 'extension',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1369,
            'endLine' => 1369,
            'startColumn' => 41,
            'endColumn' => 50,
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
 * Register a custom validator extension.
 *
 * @param  string  $rule
 * @param  \\Closure|string  $extension
 * @return void
 */',
        'startLine' => 1369,
        'endLine' => 1372,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addImplicitExtension' => 
      array (
        'name' => 'addImplicitExtension',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1381,
            'endLine' => 1381,
            'startColumn' => 42,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'extension' => 
          array (
            'name' => 'extension',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1381,
            'endLine' => 1381,
            'startColumn' => 49,
            'endColumn' => 58,
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
 * Register a custom implicit validator extension.
 *
 * @param  string  $rule
 * @param  \\Closure|string  $extension
 * @return void
 */',
        'startLine' => 1381,
        'endLine' => 1386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addDependentExtension' => 
      array (
        'name' => 'addDependentExtension',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1395,
            'endLine' => 1395,
            'startColumn' => 43,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'extension' => 
          array (
            'name' => 'extension',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1395,
            'endLine' => 1395,
            'startColumn' => 50,
            'endColumn' => 59,
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
 * Register a custom dependent validator extension.
 *
 * @param  string  $rule
 * @param  \\Closure|string  $extension
 * @return void
 */',
        'startLine' => 1395,
        'endLine' => 1400,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addReplacers' => 
      array (
        'name' => 'addReplacers',
        'parameters' => 
        array (
          'replacers' => 
          array (
            'name' => 'replacers',
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
            'startLine' => 1408,
            'endLine' => 1408,
            'startColumn' => 34,
            'endColumn' => 49,
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
 * Register an array of custom validator message replacers.
 *
 * @param  array  $replacers
 * @return void
 */',
        'startLine' => 1408,
        'endLine' => 1417,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addReplacer' => 
      array (
        'name' => 'addReplacer',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1426,
            'endLine' => 1426,
            'startColumn' => 33,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'replacer' => 
          array (
            'name' => 'replacer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1426,
            'endLine' => 1426,
            'startColumn' => 40,
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
 * Register a custom validator message replacer.
 *
 * @param  string  $rule
 * @param  \\Closure|string  $replacer
 * @return void
 */',
        'startLine' => 1426,
        'endLine' => 1429,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setCustomMessages' => 
      array (
        'name' => 'setCustomMessages',
        'parameters' => 
        array (
          'messages' => 
          array (
            'name' => 'messages',
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
            'startLine' => 1437,
            'endLine' => 1437,
            'startColumn' => 39,
            'endColumn' => 53,
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
 * Set the custom messages for the validator.
 *
 * @param  array  $messages
 * @return $this
 */',
        'startLine' => 1437,
        'endLine' => 1442,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setAttributeNames' => 
      array (
        'name' => 'setAttributeNames',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
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
            'startLine' => 1450,
            'endLine' => 1450,
            'startColumn' => 39,
            'endColumn' => 55,
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
 * Set the custom attributes on the validator.
 *
 * @param  array  $attributes
 * @return $this
 */',
        'startLine' => 1450,
        'endLine' => 1455,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addCustomAttributes' => 
      array (
        'name' => 'addCustomAttributes',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
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
            'startLine' => 1463,
            'endLine' => 1463,
            'startColumn' => 41,
            'endColumn' => 57,
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
 * Add custom attributes to the validator.
 *
 * @param  array  $attributes
 * @return $this
 */',
        'startLine' => 1463,
        'endLine' => 1468,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setImplicitAttributesFormatter' => 
      array (
        'name' => 'setImplicitAttributesFormatter',
        'parameters' => 
        array (
          'formatter' => 
          array (
            'name' => 'formatter',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1476,
                'endLine' => 1476,
                'startTokenPos' => 5684,
                'startFilePos' => 38503,
                'endTokenPos' => 5684,
                'endFilePos' => 38506,
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
                      'name' => 'callable',
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
            'startLine' => 1476,
            'endLine' => 1476,
            'startColumn' => 52,
            'endColumn' => 78,
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
 * Set the callback that used to format an implicit attribute.
 *
 * @param  callable|null  $formatter
 * @return $this
 */',
        'startLine' => 1476,
        'endLine' => 1481,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setValueNames' => 
      array (
        'name' => 'setValueNames',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 1489,
            'endLine' => 1489,
            'startColumn' => 35,
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
        'docComment' => '/**
 * Set the custom values on the validator.
 *
 * @param  array  $values
 * @return $this
 */',
        'startLine' => 1489,
        'endLine' => 1494,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'addCustomValues' => 
      array (
        'name' => 'addCustomValues',
        'parameters' => 
        array (
          'customValues' => 
          array (
            'name' => 'customValues',
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
            'startLine' => 1502,
            'endLine' => 1502,
            'startColumn' => 37,
            'endColumn' => 55,
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
 * Add the custom values for the validator.
 *
 * @param  array  $customValues
 * @return $this
 */',
        'startLine' => 1502,
        'endLine' => 1507,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setFallbackMessages' => 
      array (
        'name' => 'setFallbackMessages',
        'parameters' => 
        array (
          'messages' => 
          array (
            'name' => 'messages',
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
            'startLine' => 1515,
            'endLine' => 1515,
            'startColumn' => 41,
            'endColumn' => 55,
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
 * Set the fallback messages for the validator.
 *
 * @param  array  $messages
 * @return void
 */',
        'startLine' => 1515,
        'endLine' => 1518,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getPresenceVerifier' => 
      array (
        'name' => 'getPresenceVerifier',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1528,
                'endLine' => 1528,
                'startTokenPos' => 5813,
                'startFilePos' => 39659,
                'endTokenPos' => 5813,
                'endFilePos' => 39662,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1528,
            'endLine' => 1528,
            'startColumn' => 41,
            'endColumn' => 58,
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
 * Get the Presence Verifier implementation.
 *
 * @param  string|null  $connection
 * @return \\Illuminate\\Validation\\PresenceVerifierInterface
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 1528,
        'endLine' => 1539,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setPresenceVerifier' => 
      array (
        'name' => 'setPresenceVerifier',
        'parameters' => 
        array (
          'presenceVerifier' => 
          array (
            'name' => 'presenceVerifier',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Validation\\PresenceVerifierInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1547,
            'endLine' => 1547,
            'startColumn' => 41,
            'endColumn' => 83,
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
 * Set the Presence Verifier implementation.
 *
 * @param  \\Illuminate\\Validation\\PresenceVerifierInterface  $presenceVerifier
 * @return void
 */',
        'startLine' => 1547,
        'endLine' => 1550,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getException' => 
      array (
        'name' => 'getException',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the exception to throw upon failed validation.
 *
 * @return class-string<\\Illuminate\\Validation\\ValidationException>
 */',
        'startLine' => 1557,
        'endLine' => 1560,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setException' => 
      array (
        'name' => 'setException',
        'parameters' => 
        array (
          'exception' => 
          array (
            'name' => 'exception',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1570,
            'endLine' => 1570,
            'startColumn' => 34,
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
 * Set the exception to throw upon failed validation.
 *
 * @param  class-string<\\Illuminate\\Validation\\ValidationException>  $exception
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 1570,
        'endLine' => 1581,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'ensureExponentWithinAllowedRangeUsing' => 
      array (
        'name' => 'ensureExponentWithinAllowedRangeUsing',
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
            'startLine' => 1589,
            'endLine' => 1589,
            'startColumn' => 59,
            'endColumn' => 67,
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
 * Ensure exponents are within range using the given callback.
 *
 * @param  callable(int $scale, string $attribute, mixed $value)  $callback
 * @return $this
 */',
        'startLine' => 1589,
        'endLine' => 1594,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'getTranslator' => 
      array (
        'name' => 'getTranslator',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the Translator implementation.
 *
 * @return \\Illuminate\\Contracts\\Translation\\Translator
 */',
        'startLine' => 1601,
        'endLine' => 1604,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setTranslator' => 
      array (
        'name' => 'setTranslator',
        'parameters' => 
        array (
          'translator' => 
          array (
            'name' => 'translator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Translation\\Translator',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1612,
            'endLine' => 1612,
            'startColumn' => 35,
            'endColumn' => 56,
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
 * Set the Translator implementation.
 *
 * @param  \\Illuminate\\Contracts\\Translation\\Translator  $translator
 * @return void
 */',
        'startLine' => 1612,
        'endLine' => 1615,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'setContainer' => 
      array (
        'name' => 'setContainer',
        'parameters' => 
        array (
          'container' => 
          array (
            'name' => 'container',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Container\\Container',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1623,
            'endLine' => 1623,
            'startColumn' => 34,
            'endColumn' => 53,
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
 * Set the IoC container instance.
 *
 * @param  \\Illuminate\\Contracts\\Container\\Container  $container
 * @return void
 */',
        'startLine' => 1623,
        'endLine' => 1626,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'callExtension' => 
      array (
        'name' => 'callExtension',
        'parameters' => 
        array (
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1635,
            'endLine' => 1635,
            'startColumn' => 38,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1635,
            'endLine' => 1635,
            'startColumn' => 45,
            'endColumn' => 55,
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
 * Call a custom validator extension.
 *
 * @param  string  $rule
 * @param  array  $parameters
 * @return bool|null
 */',
        'startLine' => 1635,
        'endLine' => 1644,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'callClassBasedExtension' => 
      array (
        'name' => 'callClassBasedExtension',
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
            'startLine' => 1653,
            'endLine' => 1653,
            'startColumn' => 48,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1653,
            'endLine' => 1653,
            'startColumn' => 59,
            'endColumn' => 69,
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
 * Call a class based validator extension.
 *
 * @param  string  $callback
 * @param  array  $parameters
 * @return bool
 */',
        'startLine' => 1653,
        'endLine' => 1658,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'flushState' => 
      array (
        'name' => 'flushState',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush the validator\'s global state.
 *
 * @return void
 */',
        'startLine' => 1665,
        'endLine' => 1668,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1679,
            'endLine' => 1679,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1679,
            'endLine' => 1679,
            'startColumn' => 37,
            'endColumn' => 47,
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
 * Handle dynamic calls to class methods.
 *
 * @param  string  $method
 * @param  array  $parameters
 * @return mixed
 *
 * @throws \\BadMethodCallException
 */',
        'startLine' => 1679,
        'endLine' => 1690,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Validation\\Validator',
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