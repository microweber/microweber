<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Secrets;

use Illuminate\Support\Str;
use RuntimeException;

class PassSecretStore
{
    public const REFERENCE_PREFIX = 'pass://';

    /**
     * @var array<string, string>
     */
    private const AI_PROVIDER_SECRET_MAP = [
        'openai_api_key' => 'openai',
        'gemini_api_key' => 'gemini',
        'openrouter_api_key' => 'openrouter',
        'anthropic_api_key' => 'anthropic',
        'replicate_api_key' => 'replicate',
        'supadata_api_key' => 'supadata',
        'tavily_api_key' => 'tavily',
        'fal_api_key' => 'fal',
    ];

    public function __construct(
        private readonly PassCommandRunner $runner,
    ) {
    }

    public function isEnabled(): bool
    {
        return config('modules.ai.secret_store.driver') === 'pass'
            && (bool) config('modules.ai.secret_store.pass.enabled', false);
    }

    /**
     * @return array<string, string>
     */
    public function aiProviderSecretMap(): array
    {
        return self::AI_PROVIDER_SECRET_MAP;
    }

    public function isAiProviderSecret(string $optionKey): bool
    {
        return array_key_exists($optionKey, self::AI_PROVIDER_SECRET_MAP);
    }

    public function isReference(?string $value): bool
    {
        return is_string($value) && str_starts_with($value, self::REFERENCE_PREFIX);
    }

    public function storeAiProviderSecret(string $optionKey, string $secret): string
    {
        if (! $this->isAiProviderSecret($optionKey)) {
            throw new RuntimeException("Unsupported AI provider secret key [{$optionKey}].");
        }

        return $this->store('ai', self::AI_PROVIDER_SECRET_MAP[$optionKey], $secret);
    }

    public function resolveAiProviderSecret(string $optionKey, ?string $storedValue, ?callable $persistReference = null): ?string
    {
        if (blank($storedValue)) {
            return null;
        }

        if ($this->isReference($storedValue)) {
            return $this->isEnabled() ? $this->get($storedValue) : null;
        }

        if (! $this->isEnabled()) {
            return $storedValue;
        }

        $reference = $this->storeAiProviderSecret($optionKey, $storedValue);

        if ($persistReference !== null) {
            $persistReference($reference);
        }

        return $storedValue;
    }

    public function store(string $namespace, string $name, string $secret): string
    {
        $this->guardEnabled();

        $path = $this->path($namespace, $name);
        $this->runner->run(['insert', '-m', '-f', $path], $secret);

        return $this->reference($path);
    }

    public function get(string $reference): ?string
    {
        $this->guardEnabled();

        return $this->runner->run(['show', $this->pathFromReference($reference)]);
    }

    public function delete(string $reference): void
    {
        $this->guardEnabled();

        $this->runner->run(['rm', '-f', $this->pathFromReference($reference)]);
    }

    public function path(string $namespace, string $name): string
    {
        $prefix = trim((string) config('modules.ai.secret_store.pass.path_prefix', 'microweber'), '/');
        $environment = trim((string) config('modules.ai.secret_store.pass.environment', app()->environment()), '/');

        return collect([$prefix, $environment, $this->sanitizeSegment($namespace), $this->sanitizeSegment($name)])
            ->filter(static fn (string $segment): bool => $segment !== '')
            ->implode('/');
    }

    public function reference(string $path): string
    {
        return self::REFERENCE_PREFIX . ltrim($path, '/');
    }

    public function pathFromReference(string $reference): string
    {
        if (! $this->isReference($reference)) {
            throw new RuntimeException('The provided secret reference is invalid.');
        }

        return Str::after($reference, self::REFERENCE_PREFIX);
    }

    private function guardEnabled(): void
    {
        if (! $this->isEnabled()) {
            throw new RuntimeException('The pass secret store is not enabled.');
        }
    }

    private function sanitizeSegment(string $segment): string
    {
        return trim((string) Str::of($segment)
            ->lower()
            ->replace(['\\', ' '], '-')
            ->replaceMatches('/[^a-z0-9_\-\/]/', '-'), '-/');
    }
}
