<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Guard that the Live-Edit agent knows the animate.css preset effects.
 *
 * The Element-Style-Editor "Animations" panel offers named animate.css effects
 * (tada, pulse, bounce, …). The agent prompt must carry that vocabulary and the
 * application mechanism (animate__animated animate__<effect>), so the model can
 * apply a specific named effect the user asks for. This pins the guidance.
 */
final class LiveEditAnimateCssEffectsContractTest extends TestCase
{
    private string $src = '';

    protected function setUp(): void
    {
        $this->src = (string) file_get_contents(dirname(__DIR__, 4) . '/Modules/Ai/Agents/LiveEditAgent.php');
    }

    #[Test]
    public function agent_knows_the_animate_css_application_pattern(): void
    {
        $this->assertStringContainsString('animate__animated animate__', $this->src,
            'The agent must know the animate.css class pattern (animate__animated animate__<effect>).');
        $this->assertMatchesRegularExpression('~animate\.css~i', $this->src,
            'The agent prompt must reference animate.css.');
    }

    #[Test]
    public function agent_knows_the_attention_seeker_effect_names(): void
    {
        // The distinctive ESE effects the user called out ("tada etc").
        foreach (['tada', 'pulse', 'bounce', 'rubberBand', 'heartBeat'] as $effect) {
            $this->assertStringContainsString($effect, $this->src, "The agent prompt must list the '{$effect}' effect.");
        }
    }

    #[Test]
    public function agent_knows_how_to_apply_an_effect_to_an_existing_element(): void
    {
        // Via apply_js (add the classes to a selector, entrance inside an observer).
        $this->assertMatchesRegularExpression('~existing element.*apply_js|apply_js.*add them to the selector~is', $this->src,
            'The agent prompt must explain applying an effect to an existing element via apply_js.');
    }
}
