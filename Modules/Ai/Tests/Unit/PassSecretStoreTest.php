<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Unit;

use Modules\Ai\Services\Secrets\PassCommandRunner;
use Modules\Ai\Services\Secrets\PassSecretStore;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PassSecretStoreTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'modules.ai.secret_store.driver' => 'pass',
            'modules.ai.secret_store.pass.enabled' => true,
            'modules.ai.secret_store.pass.path_prefix' => 'microweber',
            'modules.ai.secret_store.pass.environment' => 'testing',
        ]);
    }

    #[Test]
    public function it_stores_ai_provider_secrets_in_pass_and_returns_a_reference(): void
    {
        $runner = $this->createMock(PassCommandRunner::class);
        $runner->expects($this->once())
            ->method('run')
            ->with(
                ['insert', '-m', '-f', 'microweber/testing/ai/openai'],
                'secret-value',
            )
            ->willReturn('');

        $store = new PassSecretStore($runner);

        $reference = $store->storeAiProviderSecret('openai_api_key', 'secret-value');

        $this->assertSame('pass://microweber/testing/ai/openai', $reference);
    }

    #[Test]
    public function it_reads_secrets_from_a_pass_reference(): void
    {
        $runner = $this->createMock(PassCommandRunner::class);
        $runner->expects($this->once())
            ->method('run')
            ->with(['show', 'microweber/testing/ai/openai'], null)
            ->willReturn('resolved-secret');

        $store = new PassSecretStore($runner);

        $secret = $store->get('pass://microweber/testing/ai/openai');

        $this->assertSame('resolved-secret', $secret);
    }

    #[Test]
    public function it_recognizes_ai_provider_secret_keys(): void
    {
        $store = new PassSecretStore($this->createMock(PassCommandRunner::class));

        $this->assertTrue($store->isAiProviderSecret('openai_api_key'));
        $this->assertFalse($store->isAiProviderSecret('ollama_api_url'));
    }
}
