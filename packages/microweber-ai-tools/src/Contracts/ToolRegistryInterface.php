<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Contracts;

/**
 * Central registry for discovering, registering, and resolving AI tools.
 */
interface ToolRegistryInterface
{
    /**
     * Register a tool by class name. A prototype instance is created to
     * resolve the tool name; subsequent make() calls create fresh instances.
     *
     * @param class-string<ToolInterface> $toolClass
     */
    public function register(string $toolClass): void;

    /**
     * Register an already-constructed tool instance (stores both instance
     * and class for later make()).
     */
    public function registerInstance(ToolInterface $tool): void;

    /**
     * Register a callable factory that produces tool instances.
     *
     * @param callable(array<string, mixed>): ToolInterface $factory
     */
    public function registerFactory(string $name, callable $factory): void;

    /**
     * Unregister a tool by name.
     */
    public function unregister(string $toolName): void;

    /**
     * Get a registered prototype instance by name (or alias).
     */
    public function get(string $name): ?ToolInterface;

    /**
     * Create a fresh tool instance by name, optionally with constructor dependencies.
     *
     * @param array<string, mixed> $dependencies
     */
    public function make(string $name, array $dependencies = []): ?ToolInterface;

    /**
     * All registered prototype tools keyed by name.
     *
     * @return array<string, ToolInterface>
     */
    public function all(): array;

    /**
     * Tools filtered by domain.
     *
     * @return array<string, ToolInterface>
     */
    public function getByDomain(string $domain): array;

    /**
     * Whether a tool (or alias) is registered.
     */
    public function has(string $name): bool;

    /**
     * Registered tool names.
     *
     * @return list<string>
     */
    public function names(): array;

    /**
     * Register multiple tool classes.
     *
     * @param list<class-string<ToolInterface>> $toolClasses
     */
    public function registerMany(array $toolClasses): void;

    /**
     * Remove all tools and aliases.
     */
    public function clear(): void;

    /**
     * Register an alias pointing to an existing tool name.
     */
    public function registerAlias(string $alias, string $toolName): void;

    /**
     * @return array<string, string>
     */
    public function getAliases(): array;

    /**
     * @return array<string, ToolInterface>
     */
    public function getByPermission(string $permission): array;

    /**
     * @return array<string, ToolInterface>
     */
    public function getAuthorized(): array;

    public function count(): int;

    /**
     * Unique domains among registered tools.
     *
     * @return list<string>
     */
    public function getDomains(): array;

    /**
     * Resolve the class name for a registered tool, if known.
     *
     * @return class-string<ToolInterface>|null
     */
    public function getClass(string $name): ?string;
}
