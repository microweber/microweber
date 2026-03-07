<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/MailTemplate/Support/helpers.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-get_mail_template_by_id
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-2a9a52355dc5aeb738db9a968243a53a6d86290790cf60740287bd7848464208',
   'data' => 
  array (
    'name' => 'get_mail_template_by_id',
    'parameters' => 
    array (
      'id' => 
      array (
        'name' => 'id',
        'default' => NULL,
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 156,
        'endLine' => 156,
        'startColumn' => 38,
        'endColumn' => 40,
        'parameterIndex' => 0,
        'isOptional' => false,
      ),
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
              'name' => 'Modules\\MailTemplate\\Models\\MailTemplate',
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
    'attributes' => 
    array (
    ),
    'docComment' => '/**
 * Get a mail template by ID
 *
 * @param $id Template ID
 * @return \\Modules\\MailTemplate\\Models\\MailTemplate|null
 */',
    'startLine' => 156,
    'endLine' => 159,
    'startColumn' => 5,
    'endColumn' => 5,
    'couldThrow' => false,
    'isClosure' => false,
    'isGenerator' => false,
    'isVariadic' => false,
    'isStatic' => false,
    'namespace' => NULL,
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'get_mail_template_by_id',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/MailTemplate/Support/helpers.php',
      ),
    ),
  ),
));