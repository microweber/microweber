<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Contracts;

/**
 * Interface for all AI tools in the ecosystem.
 *
 * This interface defines the contract that all tools must implement
 * to be registered and executed within the AI Tools framework.
 */
interface ToolInterface
{
    /**
     * Get the unique name of the tool.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the description of what the tool does.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get the domain/category this tool belongs to.
     *
     * @return string
     */
    public function getDomain(): string;

    /**
     * Get the required permissions to use this tool.
     *
     * @return array
     */
    public function getRequiredPermissions(): array;

    /**
     * Check if the current user is authorized to use this tool.
     *
     * @return bool
     */
    public function isAuthorized(): bool;

    /**
     * Get the tool's input properties/schema.
     *
     * @return array
     */
    public function getProperties(): array;

    /**
     * Execute the tool with the provided arguments.
     *
     * @param mixed ...$args
     * @return string
     */
    public function __invoke(...$args): string;

    /**
     * Get the maximum number of retry attempts.
     *
     * @return int|null
     */
    public function getMaxTries(): ?int;
}
