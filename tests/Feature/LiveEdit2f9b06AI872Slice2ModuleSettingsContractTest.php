<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * task-2026-05-22-2f9b06 / AI-872 Slice 2
 *
 * Filament module settings panels for Video / Slider / GoogleMaps / Embed.
 *
 * Video:      all 4 prescribed fields already existed (autoplay, muted, loop,
 *             hide_controls); task marker added, no new fields needed.
 * Slider:     new "Playback" tab with Autoplay Toggle, Speed Select, Navigation
 *             arrows Toggle, Pagination dots Toggle.
 * GoogleMaps: options.data-zoom upgraded from TextInput to Select (5/10/12/15/18);
 *             options.data-maptype Select and options.data-show-marker Toggle added.
 * Embed:      options.code_type Select (HTML/CSS/JavaScript) prepended;
 *             textarea gets resize:vertical + min-height:120px via extraInputAttributes.
 */
class LiveEdit2f9b06AI872Slice2ModuleSettingsContractTest extends TestCase
{
    private string $video;
    private string $slider;
    private string $maps;
    private string $embed;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video  = (string) file_get_contents(base_path('Modules/Video/Filament/VideoModuleSettings.php'));
        $this->slider = (string) file_get_contents(base_path('Modules/Slider/Filament/SliderModuleSettings.php'));
        $this->maps   = (string) file_get_contents(base_path('Modules/GoogleMaps/Filament/GoogleMapsModuleSettings.php'));
        $this->embed  = (string) file_get_contents(base_path('Modules/Embed/Filament/EmbedModuleSettings.php'));
    }

    // ─── Video: all 4 fields present + task marker ───────────────────────────

    #[Test]
    public function video_autoplay_field_present(): void
    {
        $this->assertStringContainsString("'options.autoplay'", $this->video,
            'VideoModuleSettings must have options.autoplay Toggle.');
    }

    #[Test]
    public function video_muted_field_present(): void
    {
        $this->assertStringContainsString("'options.muted'", $this->video,
            'VideoModuleSettings must have options.muted Toggle.');
    }

    #[Test]
    public function video_loop_field_present(): void
    {
        $this->assertStringContainsString("'options.loop'", $this->video,
            'VideoModuleSettings must have options.loop Toggle.');
    }

    #[Test]
    public function video_controls_field_present(): void
    {
        // hide_controls is the semantic inverse of "Show controls" from the spec.
        $this->assertStringContainsString("'options.hide_controls'", $this->video,
            'VideoModuleSettings must have options.hide_controls Toggle (semantic inverse of "Show controls").');
    }

    #[Test]
    public function video_task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-slice2-ai872', $this->video,
            'VideoModuleSettings must carry the AI-872 Slice 2 task-id marker.');
    }

    // ─── Slider: new Playback tab fields ─────────────────────────────────────

    #[Test]
    public function slider_autoplay_toggle_present(): void
    {
        $this->assertStringContainsString("'options.autoplay'", $this->slider,
            'SliderModuleSettings must have options.autoplay Toggle.');
    }

    #[Test]
    public function slider_autoplay_speed_select_present(): void
    {
        $this->assertStringContainsString("'options.autoplay_speed'", $this->slider,
            'SliderModuleSettings must have options.autoplay_speed Select.');
    }

    #[Test]
    public function slider_speed_options_include_all_prescribed_values(): void
    {
        // AI-1013 / task-2026-05-22 pin-evolution: preset Select replaced with free TextInput.
        // Verify the TextInput carries the numeric constraints (min 500 / max 30000 / step 500)
        // and default 3000 instead of specific preset option values.
        $this->assertStringContainsString('->minValue(500)', $this->slider, 'Speed TextInput must have minValue(500).');
        $this->assertStringContainsString('->maxValue(30000)', $this->slider, 'Speed TextInput must have maxValue(30000).');
        $this->assertStringContainsString('->step(500)', $this->slider, 'Speed TextInput must have step(500).');
        $this->assertStringContainsString("'3000'", $this->slider, 'Speed TextInput must have default 3000ms.');
    }

    #[Test]
    public function slider_show_arrows_toggle_present(): void
    {
        $this->assertStringContainsString("'options.show_arrows'", $this->slider,
            'SliderModuleSettings must have options.show_arrows Toggle.');
    }

    #[Test]
    public function slider_show_dots_toggle_present(): void
    {
        $this->assertStringContainsString("'options.show_dots'", $this->slider,
            'SliderModuleSettings must have options.show_dots Toggle.');
    }

    #[Test]
    public function slider_playback_tab_has_live_fields(): void
    {
        // All new fields must call ->live() for reactive preview.
        $playbackPos = strpos($this->slider, 'task-2026-05-22-slice2-ai872');
        $this->assertNotFalse($playbackPos, 'AI-872 Slice 2 task marker must be in SliderModuleSettings.');

        $slice = substr($this->slider, $playbackPos, 2500);
        $liveCount = substr_count($slice, '->live()');
        $this->assertGreaterThanOrEqual(4, $liveCount,
            'All 4 new Slider Playback fields must call ->live().');
    }

    #[Test]
    public function slider_task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-slice2-ai872', $this->slider,
            'SliderModuleSettings must carry the AI-872 Slice 2 task-id marker.');
    }

    // ─── GoogleMaps: zoom Select + map type + marker ─────────────────────────

    #[Test]
    public function maps_zoom_is_select_not_text_input(): void
    {
        // Must have Select for data-zoom.
        $this->assertMatchesRegularExpression(
            '~Select::make\(\'options\.data-zoom\'\)~',
            $this->maps,
            'GoogleMapsModuleSettings options.data-zoom must be a Select (not TextInput).'
        );
    }

    #[Test]
    public function maps_zoom_options_include_all_prescribed_values(): void
    {
        $this->assertStringContainsString("'5'", $this->maps,  'Zoom option 5 must be present.');
        $this->assertStringContainsString("'10'", $this->maps, 'Zoom option 10 must be present.');
        $this->assertStringContainsString("'12'", $this->maps, 'Zoom option 12 must be present.');
        $this->assertStringContainsString("'15'", $this->maps, 'Zoom option 15 must be present.');
        $this->assertStringContainsString("'18'", $this->maps, 'Zoom option 18 must be present.');
    }

    #[Test]
    public function maps_maptype_select_present(): void
    {
        // AI-1017 / task-2026-05-22 pin-evolution: key was data-maptype (no hyphen),
        // render() reads data-map-type (with hyphen). Key changed.
        $this->assertStringContainsString("'options.data-map-type'", $this->maps,
            'GoogleMapsModuleSettings must have options.data-map-type Select (AI-1017 key rename).');
    }

    #[Test]
    public function maps_maptype_options_include_all_prescribed_values(): void
    {
        $this->assertStringContainsString("'roadmap'",  $this->maps, 'Map type roadmap must be present.');
        $this->assertStringContainsString("'satellite'", $this->maps, 'Map type satellite must be present.');
        $this->assertStringContainsString("'terrain'",  $this->maps, 'Map type terrain must be present.');
        $this->assertStringContainsString("'hybrid'",   $this->maps, 'Map type hybrid must be present.');
    }

    #[Test]
    public function maps_show_marker_toggle_present(): void
    {
        $this->assertStringContainsString("'options.data-show-marker'", $this->maps,
            'GoogleMapsModuleSettings must have options.data-show-marker Toggle.');
    }

    #[Test]
    public function maps_task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-slice2-ai872', $this->maps,
            'GoogleMapsModuleSettings must carry the AI-872 Slice 2 task-id marker.');
    }

    // ─── Embed: code type Select + textarea resize ───────────────────────────

    #[Test]
    public function embed_code_type_select_present(): void
    {
        $this->assertStringContainsString("'options.code_type'", $this->embed,
            'EmbedModuleSettings must have options.code_type Select.');
    }

    #[Test]
    public function embed_code_type_options_present(): void
    {
        $this->assertStringContainsString("'html'",       $this->embed, 'Code type HTML must be present.');
        $this->assertStringContainsString("'css'",        $this->embed, 'Code type CSS must be present.');
        $this->assertStringContainsString("'javascript'", $this->embed, 'Code type JavaScript must be present.');
    }

    #[Test]
    public function embed_textarea_has_resize_attribute(): void
    {
        $this->assertStringContainsString('resize: vertical', $this->embed,
            'EmbedModuleSettings textarea must have resize:vertical via extraInputAttributes.');
        $this->assertStringContainsString('min-height: 120px', $this->embed,
            'EmbedModuleSettings textarea must have min-height:120px via extraInputAttributes.');
    }

    #[Test]
    public function embed_code_type_appears_before_textarea(): void
    {
        $selectPos  = strpos($this->embed, "'options.code_type'");
        $textareaPos = strpos($this->embed, "'options.source_code'");
        $this->assertNotFalse($selectPos,  'options.code_type must exist.');
        $this->assertNotFalse($textareaPos, 'options.source_code must exist.');
        $this->assertLessThan($textareaPos, $selectPos,
            'options.code_type Select must appear before options.source_code Textarea.');
    }

    #[Test]
    public function embed_task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-slice2-ai872', $this->embed,
            'EmbedModuleSettings must carry the AI-872 Slice 2 task-id marker.');
    }
}
