<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-119 / AI-106 / TICKET-AZ — event-bus replay buffer
 * regression coverage.
 *
 * Pins:
 *   - `MicroweberBaseClass` (the mw.app pub/sub class) declares a
 *     `#lastPayload` private field.
 *   - `on(e, fn, { replay: true })` is wired so late subscribers
 *     receive the last-emitted payload synchronously.
 *   - `dispatch(e, payload)` updates the replay buffer.
 *   - `clearReplayBuffer()` and `hasReplayPayload()` are exposed
 *     for tests + introspection.
 *   - The replay path is wrapped in try/catch so a throwing handler
 *     can't break the bus.
 *
 * Style after the cycle-52..118 contract tests (file-system reads only,
 * no DB touch).
 */
class EventBusReplayContractTest extends TestCase
{
    private const BASE_CLASS = 'packages/frontend-assets/resources/assets/api-core/services/containers/base-class.js';

    private function read(): string
    {
        $path = base_path(self::BASE_CLASS);
        $this->assertFileExists($path, self::BASE_CLASS . ' must exist');
        return file_get_contents($path);
    }

    #[Test]
    public function class_declares_last_payload_private_field(): void
    {
        $src = $this->read();

        $this->assertStringContainsString(
            '#lastPayload = {}',
            $src,
            'MicroweberBaseClass must declare a `#lastPayload` private field'
        );
        $this->assertStringContainsString(
            'AI-106 / TICKET-AZ',
            $src,
            'MicroweberBaseClass must carry the AI-106 audit-trail comment'
        );
    }

    #[Test]
    public function on_supports_replay_option(): void
    {
        $src = $this->read();

        // The `on()` method must accept an `options` parameter and
        // respond to `options.replay === true`.
        $this->assertMatchesRegularExpression(
            '/on\\(e,\\s*f,\\s*options\\)\\s*\\{/',
            $src,
            'on() must accept an `options` third parameter'
        );

        $this->assertMatchesRegularExpression(
            '/options\\.replay\\s*===\\s*true/',
            $src,
            'on() must check `options.replay === true` to gate the replay'
        );

        // The replay must use hasOwnProperty so a payload of
        // `undefined` / `null` / `0` / `""` still triggers replay
        // when the event has been dispatched at least once.
        $this->assertStringContainsString(
            "Object.prototype.hasOwnProperty.call(this.#lastPayload, e)",
            $src,
            'on() replay-gate must use hasOwnProperty on #lastPayload (not truthy-check; falsy payloads must still replay)'
        );

        // Replay must be wrapped in try/catch so a throwing handler
        // doesn't break the bus.
        $this->assertMatchesRegularExpression(
            '/try\\s*\\{[\\s\\S]{0,300}f\\.call\\(this,\\s*this\\.#lastPayload\\[e\\]\\)[\\s\\S]{0,200}\\}\\s*catch\\s*\\(err\\)/',
            $src,
            'on() replay must wrap the handler call in try/catch'
        );
    }

    #[Test]
    public function dispatch_updates_replay_buffer(): void
    {
        $src = $this->read();

        $this->assertMatchesRegularExpression(
            '/dispatch\\s*\\(e,\\s*f[^)]*\\)\\s*\\{[\\s\\S]{0,400}this\\.#lastPayload\\[e\\]\\s*=\\s*f/',
            $src,
            'dispatch() must update `this.#lastPayload[e] = f` so late replay subscribers can catch up'
        );
    }

    #[Test]
    public function clear_replay_buffer_and_introspection_helpers_exist(): void
    {
        $src = $this->read();

        $this->assertStringContainsString(
            'clearReplayBuffer (e)',
            $src,
            'class must expose `clearReplayBuffer(e?)` for tests + manual cleanup'
        );

        $this->assertStringContainsString(
            'hasReplayPayload (e)',
            $src,
            'class must expose `hasReplayPayload(e)` so callers can introspect whether a replay would fire'
        );
    }
}
