<?php

namespace MicroweberPackages\LaravelModules\Exceptions;

use Exception;

class ModuleDependencyException extends Exception
{
    protected array $issues = [];
    protected string $moduleName;

    /**
     * Create a new module dependency exception
     */
    public function __construct(string $message, string $moduleName, array $issues = [], int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->moduleName = $moduleName;
        $this->issues = $issues;
    }

    /**
     * Get the module name that caused the exception
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * Get the list of dependency issues
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

    /**
     * Create exception for missing dependency
     */
    public static function missingDependency(string $moduleName, string $dependencyName, string $constraint = '*'): self
    {
        return new self(
            "Module '{$moduleName}' requires '{$dependencyName}' ({$constraint}) which is not installed",
            $moduleName,
            [
                [
                    'type' => 'missing_dependency',
                    'module' => $dependencyName,
                    'constraint' => $constraint,
                ]
            ]
        );
    }

    /**
     * Create exception for version mismatch
     */
    public static function versionMismatch(string $moduleName, string $dependencyName, string $installedVersion, string $requiredConstraint): self
    {
        return new self(
            "Module '{$moduleName}' requires '{$dependencyName}' version {$requiredConstraint}, but {$installedVersion} is installed",
            $moduleName,
            [
                [
                    'type' => 'version_mismatch',
                    'module' => $dependencyName,
                    'installed_version' => $installedVersion,
                    'required_constraint' => $requiredConstraint,
                ]
            ]
        );
    }

    /**
     * Create exception for conflict
     */
    public static function conflict(string $moduleName, string $conflictModule, string $installedVersion, string $conflictConstraint): self
    {
        return new self(
            "Module '{$moduleName}' conflicts with '{$conflictModule}' version {$installedVersion} (conflicts with {$conflictConstraint})",
            $moduleName,
            [
                [
                    'type' => 'conflict',
                    'module' => $conflictModule,
                    'installed_version' => $installedVersion,
                    'conflict_constraint' => $conflictConstraint,
                ]
            ]
        );
    }

    /**
     * Create exception for dependency in use
     */
    public static function dependencyInUse(string $moduleName, string $dependentModule): self
    {
        return new self(
            "Cannot uninstall '{$moduleName}' - module '{$dependentModule}' depends on it",
            $moduleName,
            [
                [
                    'type' => 'dependency_in_use',
                    'module' => $dependentModule,
                ]
            ]
        );
    }

    /**
     * Create exception for circular dependency
     */
    public static function circularDependency(string $moduleName, array $dependencyChain): self
    {
        return new self(
            "Circular dependency detected: " . implode(' -> ', $dependencyChain) . " -> {$moduleName}",
            $moduleName,
            [
                [
                    'type' => 'circular_dependency',
                    'chain' => $dependencyChain,
                ]
            ]
        );
    }
}
