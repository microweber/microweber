<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Image-picker first-use UX regression coverage.
 *
 * Pins:
 *   - filepicker.js gains a `firstActiveComponent` setting that
 *     callers (or the global `window.MwMediaLibraryEmpty` flag)
 *     can use to flip the picker open on the "desktop" upload tab
 *     instead of the default "library" tab.
 *   - When the picker is started on "desktop" because the library
 *     was empty, the FIRST successful upload triggers a hand-off
 *     back to the "library" tab (idempotent — subsequent uploads
 *     don't re-switch).
 *   - The wire-up reads `window.MwMediaLibraryEmpty` (server-side
 *     rendered) and is fully overridable by an explicit caller-side
 *     `firstActiveComponent` value.
 *
 * Contract test style: file-system reads only, no DB touch.
 */
class ImagePickerFirstUseUxContractTest extends TestCase
{
    private const PICKER_JS = 'packages/frontend-assets/resources/assets/components/filepicker.js';

    private function read(): string
    {
        $path = base_path(self::PICKER_JS);
        $this->assertFileExists($path, self::PICKER_JS . ' must exist');
        return file_get_contents($path);
    }

    #[Test]
    public function defaults_declare_first_active_component_setting(): void
    {
        $src = $this->read();

        $this->assertStringContainsString(
            'firstActiveComponent: null',
            $src,
            self::PICKER_JS . ': defaults must declare `firstActiveComponent: null` (caller-overridable)'
        );

        $this->assertStringContainsString(
            'AI-117 / TICKET-CK (cycle-108',
            $src,
            self::PICKER_JS . ': must carry the first-use UX audit-trail marker'
        );
    }

    #[Test]
    public function unset_first_active_component_falls_back_to_mw_media_library_empty_flag(): void
    {
        $src = $this->read();

        // The fallback path: if the caller didn't set
        // firstActiveComponent AND window.MwMediaLibraryEmpty === true,
        // default to "desktop".
        $this->assertMatchesRegularExpression(
            '/this\\.settings\\.firstActiveComponent\\s*===\\s*null[\\s\\S]{0,300}window\\.MwMediaLibraryEmpty\\s*===\\s*true[\\s\\S]{0,200}firstActiveComponent\\s*=\\s*"desktop"/',
            $src,
            self::PICKER_JS . ': must derive firstActiveComponent="desktop" when MwMediaLibraryEmpty=true and caller did not specify'
        );
    }

    #[Test]
    public function navigation_init_clicks_first_active_component_tab(): void
    {
        $src = $this->read();

        // After mw.tabs() init, the picker must trigger a click on
        // the matching nav anchor.
        $this->assertMatchesRegularExpression(
            '/scope\\.settings\\.firstActiveComponent\\)\\s*\\{[\\s\\S]{0,400}js-filepicker-pick-type-tab-[\'"]?\\s*\\+\\s*\\n?\\s*scope\\.settings\\.firstActiveComponent[\\s\\S]{0,200}\\.trigger\\(\\s*[\'"]click[\'"]\\s*\\)/',
            $src,
            self::PICKER_JS . ': navigation init must trigger click on `js-filepicker-pick-type-tab-${firstActiveComponent}` after mw.tabs() init'
        );
    }

    #[Test]
    public function first_upload_after_empty_library_hands_off_to_library_tab(): void
    {
        $src = $this->read();

        // The fileUploaded callback must check
        //   - first time only (__libraryHandoffDone guard)
        //   - the picker was started on "desktop"
        //   - window.MwMediaLibraryEmpty was true
        // ... and then trigger a click on the library tab + flip the
        // global flag so subsequent uploads don't re-switch.
        $this->assertStringContainsString(
            'scope.__libraryHandoffDone',
            $src,
            self::PICKER_JS . ': fileUploaded must guard the hand-off with __libraryHandoffDone'
        );

        $this->assertMatchesRegularExpression(
            '/scope\\.settings\\.firstActiveComponent\\s*===\\s*"desktop"[\\s\\S]{0,200}window\\.MwMediaLibraryEmpty\\s*===\\s*true/',
            $src,
            self::PICKER_JS . ': hand-off must require firstActiveComponent==="desktop" AND MwMediaLibraryEmpty===true'
        );

        $this->assertStringContainsString(
            'window.MwMediaLibraryEmpty = false',
            $src,
            self::PICKER_JS . ': hand-off must flip MwMediaLibraryEmpty=false so subsequent uploads do not re-trigger'
        );

        $this->assertStringContainsString(
            'js-filepicker-pick-type-tab-library',
            $src,
            self::PICKER_JS . ': hand-off must trigger click on the library tab anchor'
        );
    }
}
