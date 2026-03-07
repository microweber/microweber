<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/Writer/IWriter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-PhpOffice\PhpSpreadsheet\Writer\IWriter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-72634e8523a6ddeefe61d0cbeb617cf56f5562206b8d523099972fb36befa1e6-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/Writer/IWriter.php',
      ),
    ),
    'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
    'name' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
    'shortName' => 'IWriter',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 87,
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
      'SAVE_WITH_CHARTS' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'name' => 'SAVE_WITH_CHARTS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1',
          'attributes' => 
          array (
            'startLine' => 9,
            'endLine' => 9,
            'startTokenPos' => 26,
            'startFilePos' => 150,
            'endTokenPos' => 26,
            'endFilePos' => 150,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 9,
        'endLine' => 9,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'DISABLE_PRECALCULATE_FORMULAE' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'name' => 'DISABLE_PRECALCULATE_FORMULAE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '2',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 11,
            'startTokenPos' => 37,
            'startFilePos' => 203,
            'endTokenPos' => 37,
            'endFilePos' => 203,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 11,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'spreadsheet' => 
          array (
            'name' => 'spreadsheet',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'PhpOffice\\PhpSpreadsheet\\Spreadsheet',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 18,
            'endLine' => 18,
            'startColumn' => 33,
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
 * IWriter constructor.
 *
 * @param Spreadsheet $spreadsheet The spreadsheet that we want to save using this Writer
 */',
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'aliasName' => NULL,
      ),
      'getIncludeCharts' => 
      array (
        'name' => 'getIncludeCharts',
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
        'docComment' => '/**
 * Write charts in workbook?
 *        If this is true, then the Writer will write definitions for any charts that exist in the PhpSpreadsheet object.
 *        If false (the default) it will ignore any charts defined in the PhpSpreadsheet object.
 */',
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'aliasName' => NULL,
      ),
      'setIncludeCharts' => 
      array (
        'name' => 'setIncludeCharts',
        'parameters' => 
        array (
          'includeCharts' => 
          array (
            'name' => 'includeCharts',
            'default' => NULL,
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
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 38,
            'endColumn' => 56,
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
 * Set write charts in workbook
 *        Set to true, to advise the Writer to include any charts that exist in the PhpSpreadsheet object.
 *        Set to false (the default) to ignore charts.
 *
 * @return $this
 */',
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 64,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'aliasName' => NULL,
      ),
      'getPreCalculateFormulas' => 
      array (
        'name' => 'getPreCalculateFormulas',
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
        'docComment' => '/**
 * Get Pre-Calculate Formulas flag
 *     If this is true (the default), then the writer will recalculate all formulae in a workbook when saving,
 *        so that the pre-calculated values are immediately available to MS Excel or other office spreadsheet
 *        viewer when opening the file
 *     If false, then formulae are not calculated on save. This is faster for saving in PhpSpreadsheet, but slower
 *        when opening the resulting file in MS Excel, because Excel has to recalculate the formulae itself.
 */',
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'aliasName' => NULL,
      ),
      'setPreCalculateFormulas' => 
      array (
        'name' => 'setPreCalculateFormulas',
        'parameters' => 
        array (
          'precalculateFormulas' => 
          array (
            'name' => 'precalculateFormulas',
            'default' => NULL,
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 45,
            'endColumn' => 70,
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
 * Set Pre-Calculate Formulas
 *        Set to true (the default) to advise the Writer to calculate all formulae on save
 *        Set to false to prevent precalculation of formulae on save.
 *
 * @param bool $precalculateFormulas Pre-Calculate Formulas?
 *
 * @return $this
 */',
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 78,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'aliasName' => NULL,
      ),
      'save' => 
      array (
        'name' => 'save',
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
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 26,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'flags' => 
          array (
            'name' => 'flags',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 67,
                'endLine' => 67,
                'startTokenPos' => 133,
                'startFilePos' => 2572,
                'endTokenPos' => 133,
                'endFilePos' => 2572,
              ),
            ),
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
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 37,
            'endColumn' => 50,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Save PhpSpreadsheet to file.
 *
 * @param resource|string $filename Name of the file to save
 * @param int $flags Flags that can change the behaviour of the Writer:
 *            self::SAVE_WITH_CHARTS                Save any charts that are defined (if the Writer supports Charts)
 *            self::DISABLE_PRECALCULATE_FORMULAE   Don\'t Precalculate formulae before saving the file
 *
 * @throws Exception
 */',
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'aliasName' => NULL,
      ),
      'getUseDiskCaching' => 
      array (
        'name' => 'getUseDiskCaching',
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
        'docComment' => '/**
 * Get use disk caching where possible?
 */',
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'aliasName' => NULL,
      ),
      'setUseDiskCaching' => 
      array (
        'name' => 'setUseDiskCaching',
        'parameters' => 
        array (
          'useDiskCache' => 
          array (
            'name' => 'useDiskCache',
            'default' => NULL,
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
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 39,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cacheDirectory' => 
          array (
            'name' => 'cacheDirectory',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 81,
                'endLine' => 81,
                'startTokenPos' => 174,
                'startFilePos' => 2931,
                'endTokenPos' => 174,
                'endFilePos' => 2934,
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
                      'name' => 'string',
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
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 59,
            'endColumn' => 88,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set use disk caching where possible?
 *
 * @param ?string $cacheDirectory Disk caching directory
 *
 * @return $this
 */',
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 96,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'aliasName' => NULL,
      ),
      'getDiskCachingDirectory' => 
      array (
        'name' => 'getDiskCachingDirectory',
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
 * Get disk caching directory.
 */',
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Writer',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Writer\\IWriter',
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