<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Unit;

use Modules\Ai\Providers\AiServiceProvider;
use Modules\Ai\Services\Secrets\PassCommandRunner;
use Modules\Ai\Services\Secrets\PassSecretStore;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AiServiceProviderSecretStoreTest extends TestCase
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
    public function it_migrates_plaintext_ai_provider_secrets_to_pass_references_when_loading_config(): void
    {
        save_option([
            'option_key' => 'openai_api_key',
            'option_value' => 'legacy-openai-key',
            'option_group' => 'ai',
        ]);

        $runner = $this->createMock(PassCommandRunner::class);
        $runner->expects($this->once())
            ->method('run')
            ->with(
                ['insert', '-m', '-f', 'microweber/testing/ai/openai'],
                'legacy-openai-key',
            )
            ->willReturn('');

        $this->app->instance(PassCommandRunner::class, $runner);
        $this->app->singleton(PassSecretStore::class, fn ($app) => new PassSecretStore($app->make(PassCommandRunner::class)));
        $provider = new AiServiceProvider($this->app);
        $provider->setAiConfig();

        $this->assertSame('legacy-openai-key', config('modules.ai.drivers.openai.api_key'));
        $this->assertSame('pass://microweber/testing/ai/openai', get_option('openai_api_key', 'ai'));
    }

    #[Test]
    public function it_resolves_pass_references_when_loading_ai_config(): void
    {
        save_option([
            'option_key' => 'openai_api_key',
            'option_value' => 'pass://microweber/testing/ai/openai',
            'option_group' => 'ai',
        ]);

        $runner = $this->createMock(PassCommandRunner::class);
        $runner->expects($this->once())
            ->method('run')
            ->with(['show', 'microweber/testing/ai/openai'], null)
            ->willReturn('stored-openai-key');

        $this->app->instance(PassCommandRunner::class, $runner);
        $this->app->singleton(PassSecretStore::class, fn ($app) => new PassSecretStore($app->make(PassCommandRunner::class)));
        $provider = new AiServiceProvider($this->app);
        $provider->setAiConfig();

        $this->assertSame('stored-openai-key', config('modules.ai.drivers.openai.api_key'));
        $this->assertSame('pass://microweber/testing/ai/openai', get_option('openai_api_key', 'ai'));
    }
}
