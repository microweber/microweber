<?php

namespace MicroweberPackages\LaravelModules\Services;

use Illuminate\Support\Facades\Cache;
use MicroweberPackages\LaravelModules\Models\ModuleDependency;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;

class ModuleDependencyService
{
    /**
     * Cache key prefix for dependency resolution
     */
    protected string $cachePrefix = 'module_dependencies_';

    /**
     * Cache duration in minutes
     */
    protected int $cacheDuration = 60;

    /**
     * Module repository instance
     */
    protected LaravelModulesFileRepository $moduleRepository;

    public function __construct(LaravelModulesFileRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Parse module.json and extract dependencies
     */
    public function parseModuleJson(string $moduleName): array
    {
        $module = $this->moduleRepository->find($moduleName);
        
        if (!$module) {
            return [];
        }

        $json = $module->json('module.json');
        $attributes = $json->getAttributes();

        $dependencies = [];

        // Parse 'require' section
        if (isset($attributes['require']) && is_array($attributes['require'])) {
            foreach ($attributes['require'] as $depName => $constraint) {
                if (is_numeric($depName)) {
                    // Simple requirement without version constraint
                    $depName = $constraint;
                    $constraint = '*';
                }
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => $constraint,
                    'dependency_type' => ModuleDependency::TYPE_REQUIRE,
                    'is_optional' => false,
                ];
            }
        }

        // Parse 'require-dev' section (optional dependencies)
        if (isset($attributes['require-dev']) && is_array($attributes['require-dev'])) {
            foreach ($attributes['require-dev'] as $depName => $constraint) {
                if (is_numeric($depName)) {
                    $depName = $constraint;
                    $constraint = '*';
                }
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => $constraint,
                    'dependency_type' => ModuleDependency::TYPE_REQUIRE,
                    'is_optional' => true,
                ];
            }
        }

        // Parse 'conflict' section
        if (isset($attributes['conflict']) && is_array($attributes['conflict'])) {
            foreach ($attributes['conflict'] as $depName => $constraint) {
                if (is_numeric($depName)) {
                    $depName = $constraint;
                    $constraint = '*';
                }
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => $constraint,
                    'dependency_type' => ModuleDependency::TYPE_CONFLICT,
                    'is_optional' => false,
                ];
            }
        }

        // Parse 'suggest' section
        if (isset($attributes['suggest']) && is_array($attributes['suggest'])) {
            foreach ($attributes['suggest'] as $depName => $description) {
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => '*',
                    'dependency_type' => ModuleDependency::TYPE_SUGGEST,
                    'is_optional' => true,
                    'description' => is_string($description) ? $description : null,
                ];
            }
        }

        // Parse 'replace' section
        if (isset($attributes['replace']) && is_array($attributes['replace'])) {
            foreach ($attributes['replace'] as $depName => $constraint) {
                if (is_numeric($depName)) {
                    $depName = $constraint;
                    $constraint = '*';
                }
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => $constraint,
                    'dependency_type' => ModuleDependency::TYPE_REPLACE,
                    'is_optional' => false,
                ];
            }
        }

        return $dependencies;
    }

    /**
     * Sync module dependencies from module.json to database
     */
    public function syncModuleDependencies(string $moduleName): void
    {
        $dependencies = $this->parseModuleJson($moduleName);

        // Clear existing dependencies for this module
        ModuleDependency::where('module_name', $moduleName)->delete();

        // Insert new dependencies
        foreach ($dependencies as $dependency) {
            ModuleDependency::create($dependency);
        }

        // Clear cache
        $this->clearDependencyCache($moduleName);
    }

    /**
     * Check if a module can be installed based on dependencies
     */
    public function canInstallModule(string $moduleName): array
    {
        $dependencies = $this->getModuleDependencies($moduleName);
        $issues = [];

        foreach ($dependencies as $dependency) {
            if ($dependency->is_optional) {
                continue;
            }

            $depModule = $this->moduleRepository->find($dependency->dependency_module_name);

            if ($dependency->dependency_type === ModuleDependency::TYPE_REQUIRE) {
                if (!$depModule) {
                    $issues[] = [
                        'type' => 'missing_dependency',
                        'module' => $dependency->dependency_module_name,
                        'message' => "Module '{$dependency->dependency_module_name}' is required but not installed",
                    ];
                } elseif (!$this->isVersionCompatible($depModule->get('version', '0.0.0'), $dependency->version_constraint)) {
                    $issues[] = [
                        'type' => 'version_mismatch',
                        'module' => $dependency->dependency_module_name,
                        'installed_version' => $depModule->get('version', '0.0.0'),
                        'required_constraint' => $dependency->version_constraint,
                        'message' => "Module '{$dependency->dependency_module_name}' version {$depModule->get('version', '0.0.0')} does not satisfy constraint {$dependency->version_constraint}",
                    ];
                }
            } elseif ($dependency->dependency_type === ModuleDependency::TYPE_CONFLICT) {
                if ($depModule && $this->isVersionCompatible($depModule->get('version', '0.0.0'), $dependency->version_constraint)) {
                    $issues[] = [
                        'type' => 'conflict',
                        'module' => $dependency->dependency_module_name,
                        'installed_version' => $depModule->get('version', '0.0.0'),
                        'conflict_constraint' => $dependency->version_constraint,
                        'message' => "Module '{$dependency->dependency_module_name}' version {$depModule->get('version', '0.0.0')} conflicts with this module",
                    ];
                }
            }
        }

        return [
            'can_install' => empty($issues),
            'issues' => $issues,
        ];
    }

    /**
     * Check if a module can be uninstalled based on dependent modules
     */
    public function canUninstallModule(string $moduleName): array
    {
        $dependents = ModuleDependency::where('dependency_module_name', $moduleName)
            ->where('dependency_type', ModuleDependency::TYPE_REQUIRE)
            ->where('is_optional', false)
            ->get();

        $issues = [];

        foreach ($dependents as $dependent) {
            $depModule = $this->moduleRepository->find($dependent->module_name);
            if ($depModule && $depModule->isEnabled()) {
                $issues[] = [
                    'type' => 'dependency_in_use',
                    'module' => $dependent->module_name,
                    'message' => "Module '{$dependent->module_name}' depends on '{$moduleName}' and must be uninstalled first",
                ];
            }
        }

        return [
            'can_uninstall' => empty($issues),
            'issues' => $issues,
        ];
    }

    /**
     * Get all dependencies for a module
     */
    public function getModuleDependencies(string $moduleName): array
    {
        $cacheKey = $this->cachePrefix . 'deps_' . $moduleName;

        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($moduleName) {
            return ModuleDependency::where('module_name', $moduleName)->get()->all();
        });
    }

    /**
     * Get all modules that depend on a given module
     */
    public function getDependentModules(string $moduleName): array
    {
        $cacheKey = $this->cachePrefix . 'dependents_' . $moduleName;

        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($moduleName) {
            return ModuleDependency::where('dependency_module_name', $moduleName)
                ->where('dependency_type', ModuleDependency::TYPE_REQUIRE)
                ->where('is_optional', false)
                ->get()
                ->all();
        });
    }

    /**
     * Check if a version satisfies a constraint (Composer-style semver)
     */
    public function isVersionCompatible(string $version, string $constraint): bool
    {
        $version = $this->normalizeVersion($version);
        $constraint = trim($constraint);

        // Wildcard constraint
        if ($constraint === '*' || $constraint === '') {
            return true;
        }

        // Handle OR constraints (e.g., ^1.0 || ^2.0)
        if (strpos($constraint, '||') !== false) {
            $orConstraints = explode('||', $constraint);
            foreach ($orConstraints as $orConstraint) {
                if ($this->isVersionCompatible($version, trim($orConstraint))) {
                    return true;
                }
            }
            return false;
        }

        // Handle AND constraints (space-separated)
        if (strpos($constraint, ' ') !== false && strpos($constraint, ',') !== false) {
            $andConstraints = preg_split('/[\s,]+/', $constraint);
            foreach ($andConstraints as $andConstraint) {
                if (!$this->isVersionCompatible($version, trim($andConstraint))) {
                    return false;
                }
            }
            return true;
        }

        // Caret (^) - Backward compatible changes
        if (strpos($constraint, '^') === 0) {
            return $this->checkCaretConstraint($version, substr($constraint, 1));
        }

        // Tilde (~) - Version with wildcard
        if (strpos($constraint, '~') === 0) {
            return $this->checkTildeConstraint($version, substr($constraint, 1));
        }

        // Comparison operators
        if (preg_match('/^(>=?|<=?|[~^])/', $constraint, $matches)) {
            $operator = $matches[1];
            $compareVersion = $this->normalizeVersion(substr($constraint, strlen($operator)));

            return $this->compareVersions($version, $compareVersion, $operator);
        }

        // Exact version match
        return version_compare($version, $this->normalizeVersion($constraint), '=');
    }

    /**
     * Check caret (^) constraint
     * e.g., ^1.2.3 allows >=1.2.3 <2.0.0
     */
    protected function checkCaretConstraint(string $version, string $constraint): bool
    {
        $constraint = $this->normalizeVersion($constraint);
        $parts = explode('.', $constraint);
        
        if (count($parts) >= 1 && $parts[0] === '0') {
            // ^0.x.y - only allow patch changes
            if (count($parts) >= 2) {
                $upper = $parts[0] . '.' . ($parts[1] + 1) . '.0';
            } else {
                $upper = '1.0.0';
            }
        } else {
            // ^x.y.z - allow minor and patch changes
            $upper = ($parts[0] + 1) . '.0.0';
        }

        return version_compare($version, $constraint, '>=') && 
               version_compare($version, $upper, '<');
    }

    /**
     * Check tilde (~) constraint
     * e.g., ~1.2.3 allows >=1.2.3 <1.3.0
     */
    protected function checkTildeConstraint(string $version, string $constraint): bool
    {
        $constraint = $this->normalizeVersion($constraint);
        $parts = explode('.', $constraint);
        
        if (count($parts) >= 2) {
            $upper = $parts[0] . '.' . ($parts[1] + 1) . '.0';
        } else {
            $upper = ($parts[0] + 1) . '.0.0';
        }

        return version_compare($version, $constraint, '>=') && 
               version_compare($version, $upper, '<');
    }

    /**
     * Compare two versions with an operator
     */
    protected function compareVersions(string $version1, string $version2, string $operator): bool
    {
        return version_compare($version1, $version2, $operator);
    }

    /**
     * Normalize version string to x.y.z format
     */
    protected function normalizeVersion(string $version): string
    {
        $version = trim($version);
        
        // Remove leading 'v' if present
        if (strpos($version, 'v') === 0) {
            $version = substr($version, 1);
        }

        $parts = explode('.', $version);
        
        // Ensure at least 3 parts (major.minor.patch)
        while (count($parts) < 3) {
            $parts[] = '0';
        }

        return implode('.', array_slice($parts, 0, 3));
    }

    /**
     * Get dependency tree for a module (recursive)
     */
    public function getDependencyTree(string $moduleName, int $depth = 0, array $visited = []): array
    {
        if ($depth > 10 || in_array($moduleName, $visited)) {
            return [];
        }

        $visited[] = $moduleName;
        $dependencies = $this->getModuleDependencies($moduleName);
        $tree = [];

        foreach ($dependencies as $dependency) {
            $depModule = $this->moduleRepository->find($dependency->dependency_module_name);
            
            $node = [
                'name' => $dependency->dependency_module_name,
                'type' => $dependency->dependency_type,
                'constraint' => $dependency->version_constraint,
                'optional' => $dependency->is_optional,
                'installed' => (bool) $depModule,
                'version' => $depModule ? $depModule->get('version', '0.0.0') : null,
                'enabled' => $depModule ? $depModule->isEnabled() : false,
                'children' => [],
            ];

            // Recursively get children for required dependencies
            if ($dependency->dependency_type === ModuleDependency::TYPE_REQUIRE && !$dependency->is_optional) {
                $node['children'] = $this->getDependencyTree($dependency->dependency_module_name, $depth + 1, $visited);
            }

            $tree[] = $node;
        }

        return $tree;
    }

    /**
     * Clear dependency cache for a module
     */
    public function clearDependencyCache(string $moduleName): void
    {
        Cache::forget($this->cachePrefix . 'deps_' . $moduleName);
        Cache::forget($this->cachePrefix . 'dependents_' . $moduleName);
    }

    /**
     * Parse module.json array directly (for testing)
     */
    public function parseModuleJsonFromArray(string $moduleName, array $json): array
    {
        $dependencies = [];

        // Parse 'require' section
        if (isset($json['require']) && is_array($json['require'])) {
            foreach ($json['require'] as $depName => $constraint) {
                if (is_numeric($depName)) {
                    // Simple requirement without version constraint
                    $depName = $constraint;
                    $constraint = '*';
                }
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => $constraint,
                    'dependency_type' => ModuleDependency::TYPE_REQUIRE,
                    'is_optional' => false,
                ];
            }
        }

        // Parse 'require-dev' section (optional dependencies)
        if (isset($json['require-dev']) && is_array($json['require-dev'])) {
            foreach ($json['require-dev'] as $depName => $constraint) {
                if (is_numeric($depName)) {
                    $depName = $constraint;
                    $constraint = '*';
                }
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => $constraint,
                    'dependency_type' => ModuleDependency::TYPE_REQUIRE,
                    'is_optional' => true,
                ];
            }
        }

        // Parse 'conflict' section
        if (isset($json['conflict']) && is_array($json['conflict'])) {
            foreach ($json['conflict'] as $depName => $constraint) {
                if (is_numeric($depName)) {
                    $depName = $constraint;
                    $constraint = '*';
                }
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => $constraint,
                    'dependency_type' => ModuleDependency::TYPE_CONFLICT,
                    'is_optional' => false,
                ];
            }
        }

        // Parse 'suggest' section
        if (isset($json['suggest']) && is_array($json['suggest'])) {
            foreach ($json['suggest'] as $depName => $description) {
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => '*',
                    'dependency_type' => ModuleDependency::TYPE_SUGGEST,
                    'is_optional' => true,
                    'description' => is_string($description) ? $description : null,
                ];
            }
        }

        // Parse 'replace' section
        if (isset($json['replace']) && is_array($json['replace'])) {
            foreach ($json['replace'] as $depName => $constraint) {
                if (is_numeric($depName)) {
                    $depName = $constraint;
                    $constraint = '*';
                }
                $dependencies[] = [
                    'module_name' => $moduleName,
                    'dependency_module_name' => $depName,
                    'version_constraint' => $constraint,
                    'dependency_type' => ModuleDependency::TYPE_REPLACE,
                    'is_optional' => false,
                ];
            }
        }

        return $dependencies;
    }

    /**
     * Sync dependencies from array (for testing)
     */
    public function syncModuleDependenciesFromArray(string $moduleName, array $json): void
    {
        $dependencies = $this->parseModuleJsonFromArray($moduleName, $json);

        // Clear existing dependencies for this module
        ModuleDependency::where('module_name', $moduleName)->delete();

        // Insert new dependencies
        foreach ($dependencies as $dependency) {
            ModuleDependency::create($dependency);
        }

        // Clear cache
        $this->clearDependencyCache($moduleName);
    }

    /**
     * Clear all dependency caches
     */
    public function clearAllCaches(): void
    {
        Cache::flush();
    }

    /**
     * Validate all module dependencies in the system
     */
    public function validateAllDependencies(): array
    {
        $modules = $this->moduleRepository->allEnabled();
        $results = [];

        foreach ($modules as $module) {
            $moduleName = $module->getName();
            $validation = $this->canInstallModule($moduleName);
            
            if (!$validation['can_install']) {
                $results[$moduleName] = $validation['issues'];
            }
        }

        return $results;
    }
}
