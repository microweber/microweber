<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Registry;

use MicroweberPackages\AiTools\Contracts\ToolInterface;
use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;

/**
 * Tool Registry for managing AI tools.
 *
 * This class provides centralized registration and discovery
 * of AI tools. Tools can be registered manually or auto-discovered.
 */
class ToolRegistry implements ToolRegistryInterface
{
    /**
     * Registered tools.
     *
     * @var array<string, ToolInterface>
     */
    protected array $tools = [];

    /**
     * Tool aliases for backward compatibility.
     *
     * @var array<string, string>
     */
    protected array $aliases = [];

    public function register(string $toolClass): void
    {
        if (!class_exists($toolClass)) {
            throw new \InvalidArgumentException("Tool class {$toolClass} does not exist");
        }

        if (!is_subclass_of($toolClass, ToolInterface::class)) {
            throw new \InvalidArgumentException("Tool class {$toolClass} must implement ToolInterface");
        }

        $tool = new $toolClass();
        $name = $tool->getName();

        $this->tools[$name] = $tool;
    }

    public function unregister(string $toolName): void
    {
        unset($this->tools[$toolName]);
    }

    public function get(string $name): ?ToolInterface
    {
        // Check for direct match
        if (isset($this->tools[$name])) {
            return $this->tools[$name];
        }

        // Check for alias
        if (isset($this->aliases[$name]) && isset($this->tools[$this->aliases[$name]])) {
            return $this->tools[$this->aliases[$name]];
        }

        return null;
    }

    public function all(): array
    {
        return $this->tools;
    }

    public function getByDomain(string $domain): array
    {
        return array_filter(
            $this->tools,
            fn(ToolInterface $tool) => $tool->getDomain() === $domain
        );
    }

    public function has(string $name): bool
    {
        return isset($this->tools[$name]) || isset($this->aliases[$name]);
    }

    public function names(): array
    {
        return array_keys($this->tools);
    }

    public function registerMany(array $toolClasses): void
    {
        foreach ($toolClasses as $toolClass) {
            $this->register($toolClass);
        }
    }

    public function clear(): void
    {
        $this->tools = [];
        $this->aliases = [];
    }

    /**
     * Register an alias for a tool.
     *
     * @param string $alias
     * @param string $toolName
     * @return void
     */
    public function registerAlias(string $alias, string $toolName): void
    {
        $this->aliases[$alias] = $toolName;
    }

    /**
     * Get all registered aliases.
     *
     * @return array
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * Get tools by required permission.
     *
     * @param string $permission
     * @return array
     */
    public function getByPermission(string $permission): array
    {
        return array_filter(
            $this->tools,
            fn(ToolInterface $tool) => in_array($permission, $tool->getRequiredPermissions())
        );
    }

    /**
     * Get tools authorized for current user.
     *
     * @return array
     */
    public function getAuthorized(): array
    {
        return array_filter(
            $this->tools,
            fn(ToolInterface $tool) => $tool->isAuthorized()
        );
    }

    /**
     * Get count of registered tools.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->tools);
    }

    /**
     * Get domains of all registered tools.
     *
     * @return array
     */
    public function getDomains(): array
    {
        $domains = array_map(
            fn(ToolInterface $tool) => $tool->getDomain(),
            $this->tools
        );

        return array_unique($domains);
    }
}
