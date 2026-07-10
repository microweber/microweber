<?php

namespace MicroweberPackages\Dusk;

class DuskCoverage
{
    /**
     * Persist collected JS coverage (window.__coverage__) to tests/coverages/js.
     *
     * Salvaged from tests/BrowserLegacy/Components/BaseComponent::saveCoverage,
     * which declared `namespace Tests\Browser\Components` at a tests/BrowserLegacy/
     * path — a PSR-4 mismatch, so the class was never autoloadable and coverage was
     * silently never written. This package class is the single, autoloadable home
     * for it; the macro, tests/DuskTestCase and the legacy BaseComponent all delegate
     * here.
     */
    public static function save($coverage): void
    {
        if (empty($coverage)) {
            return;
        }

        $dir = base_path('tests/coverages/js');

        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        file_put_contents(
            $dir . '/js-coverage-' . time() . '_' . uniqid() . '.json',
            json_encode($coverage)
        );
    }
}
