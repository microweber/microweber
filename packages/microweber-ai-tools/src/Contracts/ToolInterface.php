<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Contracts;

/**
 * Contract for all AI tools registered with the framework.
 *
 * Implementations typically extend {@see \MicroweberPackages\AiTools\Base\BaseTool}
 * which also implements NeuronAI's tool interface for agent integration.
 */
interface ToolInterface
{
    /**
     * Unique tool name used for lookup and agent function calling.
     */
    public function getName(): string;

    /**
     * Human-readable description of what the tool does.
     */
    public function getDescription(): string;

    /**
     * Domain/category this tool belongs to (e.g. content, shop, media).
     */
    public function getDomain(): string;

    /**
     * Permission names required to use this tool.
     *
     * @return list<string>
     */
    public function getRequiredPermissions(): array;

    /**
     * Whether the current context is authorized to run this tool.
     */
    public function isAuthorized(): bool;

    /**
     * Input property schema for the tool.
     *
     * @return list<object>
     */
    public function getProperties(): array;

    /**
     * Execute the tool with the provided arguments.
     *
     * @param mixed ...$args
     */
    public function __invoke(mixed ...$args): string;

    /**
     * Maximum retry attempts for agent tool calls, if any.
     */
    public function getMaxTries(): ?int;
}
