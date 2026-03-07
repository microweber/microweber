<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/./composer/src/Composer/InstalledVersions.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Composer\InstalledVersions
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1e4525198301d1ed1f87ac3df2a8555a6f139f8df30a703d0b775a61963c96e4-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Composer\\InstalledVersions',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/./composer/src/Composer/InstalledVersions.php',
      ),
    ),
    'namespace' => 'Composer',
    'name' => 'Composer\\InstalledVersions',
    'shortName' => 'InstalledVersions',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * This class is copied in every Composer installed project and available to all
 *
 * See also https://getcomposer.org/doc/07-runtime.md#installed-versions
 *
 * To require its presence, you can require `composer-runtime-api ^2.0`
 *
 * @final
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 396,
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
      'selfDir' => 
      array (
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'name' => 'selfDir',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 37,
            'startFilePos' => 830,
            'endTokenPos' => 37,
            'endFilePos' => 833,
          ),
        ),
        'docComment' => '/**
 * @var string|null if set (by reflection by Composer), this should be set to the path where this class is being copied to
 * @internal
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'installed' => 
      array (
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'name' => 'installed',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var mixed[]|null
 * @psalm-var array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>}|array{}|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'installedIsLocalDir' => 
      array (
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'name' => 'installedIsLocalDir',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'canGetVendors' => 
      array (
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'name' => 'canGetVendors',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var bool|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'installedByVendor' => 
      array (
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'name' => 'installedByVendor',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 77,
            'startFilePos' => 1973,
            'endTokenPos' => 79,
            'endFilePos' => 1979,
          ),
        ),
        'docComment' => '/**
 * @var array[]
 * @psalm-var array<string, array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>}>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 48,
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
      'getInstalledPackages' => 
      array (
        'name' => 'getInstalledPackages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a list of all package names which are present, either by being installed, replaced or provided
 *
 * @return string[]
 * @psalm-return list<string>
 */',
        'startLine' => 63,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getInstalledPackagesByType' => 
      array (
        'name' => 'getInstalledPackagesByType',
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
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 55,
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
 * Returns a list of all package names with a specific type e.g. \'library\'
 *
 * @param  string   $type
 * @return string[]
 * @psalm-return list<string>
 */',
        'startLine' => 84,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'isInstalled' => 
      array (
        'name' => 'isInstalled',
        'parameters' => 
        array (
          'packageName' => 
          array (
            'name' => 'packageName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 108,
            'endLine' => 108,
            'startColumn' => 40,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'includeDevRequirements' => 
          array (
            'name' => 'includeDevRequirements',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 108,
                'endLine' => 108,
                'startTokenPos' => 305,
                'startFilePos' => 3543,
                'endTokenPos' => 305,
                'endFilePos' => 3546,
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
            'startColumn' => 54,
            'endColumn' => 83,
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
 * Checks whether the given package is installed
 *
 * This also returns true if the package name is provided or replaced by another package
 *
 * @param  string $packageName
 * @param  bool   $includeDevRequirements
 * @return bool
 */',
        'startLine' => 108,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'satisfies' => 
      array (
        'name' => 'satisfies',
        'parameters' => 
        array (
          'parser' => 
          array (
            'name' => 'parser',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Composer\\Semver\\VersionParser',
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
            'startColumn' => 38,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'packageName' => 
          array (
            'name' => 'packageName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 131,
            'endLine' => 131,
            'startColumn' => 61,
            'endColumn' => 72,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'constraint' => 
          array (
            'name' => 'constraint',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 131,
            'endLine' => 131,
            'startColumn' => 75,
            'endColumn' => 85,
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
 * Checks whether the given package satisfies a version constraint
 *
 * e.g. If you want to know whether version 2.3+ of package foo/bar is installed, you would call:
 *
 *   Composer\\InstalledVersions::satisfies(new VersionParser, \'foo/bar\', \'^2.3\')
 *
 * @param  VersionParser $parser      Install composer/semver to have access to this class and functionality
 * @param  string        $packageName
 * @param  string|null   $constraint  A version constraint to check for, if you pass one you have to make sure composer/semver is required by your package
 * @return bool
 */',
        'startLine' => 131,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getVersionRanges' => 
      array (
        'name' => 'getVersionRanges',
        'parameters' => 
        array (
          'packageName' => 
          array (
            'name' => 'packageName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 148,
            'endLine' => 148,
            'startColumn' => 45,
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
 * Returns a version constraint representing all the range(s) which are installed for a given package
 *
 * It is easier to use this via isInstalled() with the $constraint argument if you need to check
 * whether a given version of a package is installed, and not just whether it exists
 *
 * @param  string $packageName
 * @return string Version constraint usable with composer/semver
 */',
        'startLine' => 148,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getVersion' => 
      array (
        'name' => 'getVersion',
        'parameters' => 
        array (
          'packageName' => 
          array (
            'name' => 'packageName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 179,
            'endLine' => 179,
            'startColumn' => 39,
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
 * @param  string      $packageName
 * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
 */',
        'startLine' => 179,
        'endLine' => 194,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getPrettyVersion' => 
      array (
        'name' => 'getPrettyVersion',
        'parameters' => 
        array (
          'packageName' => 
          array (
            'name' => 'packageName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 45,
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
 * @param  string      $packageName
 * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
 */',
        'startLine' => 200,
        'endLine' => 215,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getReference' => 
      array (
        'name' => 'getReference',
        'parameters' => 
        array (
          'packageName' => 
          array (
            'name' => 'packageName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 221,
            'endLine' => 221,
            'startColumn' => 41,
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
 * @param  string      $packageName
 * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as reference
 */',
        'startLine' => 221,
        'endLine' => 236,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getInstallPath' => 
      array (
        'name' => 'getInstallPath',
        'parameters' => 
        array (
          'packageName' => 
          array (
            'name' => 'packageName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 242,
            'endLine' => 242,
            'startColumn' => 43,
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
 * @param  string      $packageName
 * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as install path. Packages of type metapackages also have a null install path.
 */',
        'startLine' => 242,
        'endLine' => 253,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getRootPackage' => 
      array (
        'name' => 'getRootPackage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array
 * @psalm-return array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}
 */',
        'startLine' => 259,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getRawData' => 
      array (
        'name' => 'getRawData',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the raw installed.php data for custom implementations
 *
 * @deprecated Use getAllRawData() instead which returns all datasets for all autoloaders present in the process. getRawData only returns the first dataset loaded, which may not be what you expect.
 * @return array[]
 * @psalm-return array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>}
 */',
        'startLine' => 273,
        'endLine' => 288,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getAllRawData' => 
      array (
        'name' => 'getAllRawData',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the raw data of all installed.php which are currently loaded for custom implementations
 *
 * @return array[]
 * @psalm-return list<array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>}>
 */',
        'startLine' => 296,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'reload' => 
      array (
        'name' => 'reload',
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
            'startLine' => 319,
            'endLine' => 319,
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
 * Lets you reload the static array from another file
 *
 * This is only useful for complex integrations in which a project needs to use
 * this class but then also needs to execute another project\'s autoloader in process,
 * and wants to ensure both projects have access to their version of installed.php.
 *
 * A typical case would be PHPUnit, where it would need to make sure it reads all
 * the data it needs from this class, then call reload() with
 * `require $CWD/vendor/composer/installed.php` (or similar) as input to make sure
 * the project in which it runs can then also use this class safely, without
 * interference between PHPUnit\'s dependencies and the project\'s dependencies.
 *
 * @param  array[] $data A vendor/composer/installed.php data set
 * @return void
 *
 * @psalm-param array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>} $data
 */',
        'startLine' => 319,
        'endLine' => 329,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getSelfDir' => 
      array (
        'name' => 'getSelfDir',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 334,
        'endLine' => 341,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
        'aliasName' => NULL,
      ),
      'getInstalled' => 
      array (
        'name' => 'getInstalled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array[]
 * @psalm-return list<array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>}>
 */',
        'startLine' => 347,
        'endLine' => 395,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Composer',
        'declaringClassName' => 'Composer\\InstalledVersions',
        'implementingClassName' => 'Composer\\InstalledVersions',
        'currentClassName' => 'Composer\\InstalledVersions',
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