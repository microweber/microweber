<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Registry;

use MicroweberPackages\AiTools\Contracts\ToolInterface;
use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;

/**
 * In-memory tool registry used by Laravel apps and the Microweber CMS.
 */
class ToolRegistry implements ToolRegistryInterface
{
    /** @var array<string, ToolInterface> */
    private array $tools = [];

    /** @var array<string, class-string<ToolInterface>> */
    private array $classes = [];

    /** @var array<string, callable(array<string, mixed>): ToolInterface> */
    private array $factories = [];

    /** @var array<string, string> */
    private array $aliases = [];

    public function register(string $toolClass): void
    {
        if (!class_exists($toolClass)) {
            throw new \InvalidArgumentException("Tool class {$toolClass} does not exist");
        }

        if (!is_subclass_of($toolClass, ToolInterface::class) && $toolClass !== ToolInterface::class) {
            throw new \InvalidArgumentException("Tool class {$toolClass} must implement ToolInterface");
        }

        /** @var ToolInterface $tool */
        $tool = $this->instantiate($toolClass, []);
        $name = $tool->getName();

        $this->tools[$name] = $tool;
        $this->classes[$name] = $toolClass;
    }

    public function registerInstance(ToolInterface $tool): void
    {
        $name = $tool->getName();
        $this->tools[$name] = $tool;
        $this->classes[$name] = $tool::class;
    }

    public function registerFactory(string $name, callable $factory): void
    {
        $tool = $factory([]);
        if (!$tool instanceof ToolInterface) {
            throw new \InvalidArgumentException('Factory must return a ToolInterface instance');
        }

        $this->factories[$name] = $factory;
        $this->tools[$name] = $tool;
        $this->classes[$name] = $tool::class;
    }

    public function unregister(string $toolName): void
    {
        unset($this->tools[$toolName], $this->classes[$toolName], $this->factories[$toolName]);

        foreach ($this->aliases as $alias => $target) {
            if ($target === $toolName || $alias === $toolName) {
                unset($this->aliases[$alias]);
            }
        }
    }

    public function get(string $name): ?ToolInterface
    {
        $resolved = $this->resolveName($name);

        return $resolved !== null ? ($this->tools[$resolved] ?? null) : null;
    }

    public function make(string $name, array $dependencies = []): ?ToolInterface
    {
        $resolved = $this->resolveName($name);
        if ($resolved === null) {
            return null;
        }

        if (isset($this->factories[$resolved])) {
            $tool = ($this->factories[$resolved])($dependencies);
            if (!$tool instanceof ToolInterface) {
                throw new \RuntimeException("Factory for tool [{$resolved}] did not return ToolInterface");
            }

            return $tool;
        }

        if (isset($this->classes[$resolved])) {
            return $this->instantiate($this->classes[$resolved], $dependencies);
        }

        return $this->tools[$resolved] ?? null;
    }

    public function all(): array
    {
        return $this->tools;
    }

    public function getByDomain(string $domain): array
    {
        return array_filter(
            $this->tools,
            static fn (ToolInterface $tool): bool => $tool->getDomain() === $domain
        );
    }

    public function has(string $name): bool
    {
        return $this->resolveName($name) !== null;
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
        $this->classes = [];
        $this->factories = [];
        $this->aliases = [];
    }

    public function registerAlias(string $alias, string $toolName): void
    {
        $this->aliases[$alias] = $toolName;
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }

    public function getByPermission(string $permission): array
    {
        return array_filter(
            $this->tools,
            static fn (ToolInterface $tool): bool => in_array($permission, $tool->getRequiredPermissions(), true)
        );
    }

    public function getAuthorized(): array
    {
        return array_filter(
            $this->tools,
            static fn (ToolInterface $tool): bool => $tool->isAuthorized()
        );
    }

    public function count(): int
    {
        return count($this->tools);
    }

    public function getDomains(): array
    {
        $domains = array_map(
            static fn (ToolInterface $tool): string => $tool->getDomain(),
            $this->tools
        );

        return array_values(array_unique($domains));
    }

    public function getClass(string $name): ?string
    {
        $resolved = $this->resolveName($name);

        return $resolved !== null ? ($this->classes[$resolved] ?? null) : null;
    }

    private function resolveName(string $name): ?string
    {
        if (isset($this->tools[$name])) {
            return $name;
        }

        if (isset($this->aliases[$name]) && isset($this->tools[$this->aliases[$name]])) {
            return $this->aliases[$name];
        }

        return null;
    }

    /**
     * @param class-string<ToolInterface> $toolClass
     * @param array<string, mixed> $dependencies
     */
    private function instantiate(string $toolClass, array $dependencies): ToolInterface
    {
        // Prefer Laravel container when available (supports constructor DI).
        if (function_exists('app')) {
            try {
                $app = app();
                if (is_object($app) && method_exists($app, 'make')) {
                    /** @var ToolInterface $tool */
                    $tool = $dependencies === []
                        ? $app->make($toolClass)
                        : $app->make($toolClass, ['dependencies' => $dependencies]);

                    return $tool;
                }
            } catch (\Throwable) {
                // Fall through to reflection-based construction.
            }
        }

        $reflection = new \ReflectionClass($toolClass);

        if (!$reflection->isInstantiable()) {
            throw new \InvalidArgumentException("Tool class {$toolClass} is not instantiable");
        }

        $constructor = $reflection->getConstructor();
        if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
            /** @var ToolInterface $tool */
            $tool = $reflection->newInstance();

            return $tool;
        }

        $params = $constructor->getParameters();
        $first = $params[0];
        $type = $first->getType();

        // Most tools accept (array $dependencies = [])
        if (
            $first->getName() === 'dependencies'
            || ($type instanceof \ReflectionNamedType && $type->getName() === 'array')
        ) {
            /** @var ToolInterface $tool */
            $tool = $reflection->newInstance($dependencies);

            return $tool;
        }

        // Build args from defaults / empty dependencies bag for non-array first params
        $args = [];
        foreach ($params as $param) {
            $paramType = $param->getType();
            if ($param->getName() === 'dependencies') {
                $args[] = $dependencies;
                continue;
            }
            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }
            if ($paramType instanceof \ReflectionNamedType && $paramType->allowsNull()) {
                $args[] = null;
                continue;
            }
            // Cannot resolve — leave for exception
            break;
        }

        try {
            /** @var ToolInterface $tool */
            $tool = $reflection->newInstanceArgs($args);

            return $tool;
        } catch (\ArgumentCountError|\TypeError $e) {
            throw new \InvalidArgumentException(
                "Unable to instantiate tool class {$toolClass}: " . $e->getMessage(),
                0,
                $e
            );
        }
    }
}
