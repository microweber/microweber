<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Contracts;

/**
 * Interface for tool registry.
 *
 * The registry manages tool discovery, registration, and retrieval.
 */
interface ToolRegistryInterface
{
    /**
     * Register a tool class.
     *
     * @param string $toolClass
     * @return void
     */
    public function register(string $toolClass): void;

    /**
     * Unregister a tool.
     *
     * @param string $toolName
     * @return void
     */
    public function unregister(string $toolName): void;

    /**
     * Get a tool instance by name.
     *
     * @param string $name
     * @return ToolInterface|null
     */
    public function get(string $name): ?ToolInterface;

    /**
     * Get all registered tools.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Get tools filtered by domain.
     *
     * @param string $domain
     * @return array
     */
    public function getByDomain(string $domain): array;

    /**
     * Check if a tool is registered.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Get tool names as array.
     *
     * @return array
     */
    public function names(): array;

    /**
     * Register multiple tools at once.
     *
     * @param array $toolClasses
     * @return void
     */
    public function registerMany(array $toolClasses): void;

    /**
     * Clear all registered tools.
     *
     * @return void
     */
    public function clear(): void;
}
