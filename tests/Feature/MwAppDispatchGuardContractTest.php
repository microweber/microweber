<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-79 / AI-55 / TICKET-CC — `mw.app.dispatch()` guard regression
 * coverage.
 *
 * Pins:
 *   - `mw-core.js` (loaded on every page — public AND admin) installs
 *     a minimal no-op `mw.app` surface so public-site scripts that
 *     accidentally call `mw.app.dispatch(...)` (or `on`/`off`/`off_all`)
 *     don't throw "Cannot read properties of undefined" and break the
 *     entire page.
 *   - The shim is idempotent — if `mw.app` is already an object, or
 *     a real method is present, the guard leaves it alone. Admin
 *     Live-Edit's real event bus overrides this shim during boot.
 *
 * Style after the cycle-52..78 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class MwAppDispatchGuardContractTest extends TestCase
{
    private string $coreSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->coreSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/core/mw-core.js'
        ));
    }

    #[Test]
    public function mw_app_object_is_seeded_when_missing(): void
    {
        // Without this seed, every other check in the file would
        // dereference a missing property and throw the same error
        // the guard is trying to prevent.
        $this->assertMatchesRegularExpression(
            "/typeof\\s+mw\\.app\\s*!==\\s*'object'\\s*\\|\\|\\s*mw\\.app\\s*===\\s*null\\s*\\)\\s*\\{\\s*mw\\.app\\s*=\\s*\\{\\}/s",
            $this->coreSrc,
            'mw-core.js: must seed mw.app = {} when missing or null'
        );
    }

    #[Test]
    public function dispatch_method_is_no_op_when_real_bus_not_loaded(): void
    {
        // The dispatch shim must exist and be a function — that's the
        // primary guarantee the public site depends on.
        $this->assertMatchesRegularExpression(
            "/typeof\\s+mw\\.app\\.dispatch\\s*!==\\s*'function'\\s*\\)\\s*\\{\\s*mw\\.app\\.dispatch\\s*=\\s*function\\s*\\(\\)/s",
            $this->coreSrc,
            'mw-core.js: must install a no-op mw.app.dispatch when missing'
        );
    }

    #[Test]
    public function on_off_off_all_methods_are_also_no_op_when_missing(): void
    {
        // Public-site code that calls `mw.app.on(...)` to subscribe
        // would also throw without this. Pin the full no-op surface.
        $required = ['on', 'off', 'off_all'];
        foreach ($required as $method) {
            $this->assertMatchesRegularExpression(
                "/typeof\\s+mw\\.app\\.{$method}\\s*!==\\s*'function'\\s*\\)\\s*\\{\\s*mw\\.app\\.{$method}\\s*=\\s*function\\s*\\(\\)/s",
                $this->coreSrc,
                "mw-core.js: must install a no-op mw.app.{$method} when missing"
            );
        }
    }

    #[Test]
    public function guard_is_idempotent_via_typeof_function_check(): void
    {
        // Each shim install is gated by `typeof X !== 'function'`. If
        // the admin Live-Edit bundle has already replaced the shim
        // with the real event bus, the typeof check returns 'function'
        // and the assignment is skipped — we don't stomp the real
        // implementation.
        $this->assertSame(
            // 4 shim methods (dispatch + on + off + off_all) each
            // gated independently.
            4,
            preg_match_all(
                "/typeof\\s+mw\\.app\\.\\w+\\s*!==\\s*'function'/",
                $this->coreSrc
            ),
            'mw-core.js: must have exactly 4 typeof-function guards (dispatch, on, off, off_all)'
        );
    }

    #[Test]
    public function shim_runs_after_window_mw_initialization(): void
    {
        // The mw-core.js guard depends on `mw` being defined first
        // (`if (!window.mw) window.mw = {}`). Pin ordering: the
        // window.mw seed comes BEFORE the mw.app shim block.
        $mwSeedPos = strpos($this->coreSrc, 'window.mw = {}');
        $appShimPos = strpos($this->coreSrc, 'mw.app = {}');

        $this->assertNotFalse($mwSeedPos, 'window.mw seed must exist');
        $this->assertNotFalse($appShimPos, 'mw.app shim must exist');
        $this->assertLessThan(
            $appShimPos,
            $mwSeedPos,
            'mw-core.js: window.mw seed must come BEFORE the mw.app shim — otherwise the shim derefs an undefined parent'
        );
    }
}
