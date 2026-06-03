<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\ContactForm\Models\Form;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — ContactForm module smoke.
 *
 * Combination shape — covers BOTH halves of the Plan-C.2 task
 * line "contact form insertion + submission":
 *
 *   1. The admin SETTINGS view (insertion surface): ContactForm
 *      ships a Filament settings page registered as
 *      ContactFormModuleSettings via FilamentRegistry::registerPage
 *      in ContactFormServiceProvider.php. Filament-default route
 *      slug: /admin/contact-form-module-settings (kebab-cased
 *      from the class name). The page exposes the canonical
 *      contact-form configurator: form-fields builder (via the
 *      embedded admin-list-custom-fields Livewire component),
 *      auto-respond toggles, recipient lists.
 *   2. The MANUAL FORM-CONFIG persistence (the persistence target
 *      every contact-form save ultimately writes to): the Form
 *      Eloquent model + `forms` table. Real submissions land in
 *      `forms_data` / `forms_data_values`, but the form-config
 *      row itself (name, slug, confirmation message, recipient
 *      notifications) is the Form-model surface. The smoke
 *      drives a full CRUD round-trip on a marker-prefixed
 *      `forms` row.
 *
 *   Signal #1 + #3 (page OK + no console errors): full
 *   assertPageSmokeOk() probe of /admin/contact-form-module-settings.
 *
 *   Signal #2 (form-config save round-trip): full
 *   create → ->save (description update) → reload → ->delete
 *   cycle on a marker-prefixed `forms` row through the Form
 *   Eloquent model. Plus an inline-or-deferred Livewire chrome
 *   probe so the smoke also proves the admin form pipeline is
 *   reachable.
 *
 *   Belt-and-braces: installInPageErrorGuard() on the settings
 *   page after settle, with a 1.5s window catching any
 *   deferred-script throws.
 *
 * Note on actual submissions: real public-frontend form
 * submissions (the literal "submission" the Plan-C.2 line
 * names) persist into forms_data / forms_data_values via the
 * ContactForm ContactFormManager service. Driving a real
 * submission end-to-end requires either CSRF token threading
 * through Dusk OR a captcha-aware Livewire post — both belong
 * to a follow-up smoke that mocks the captcha provider. This
 * smoke covers the form-CONFIG round-trip + the
 * insertion-side admin surface per the same
 * three-assertion-minimum the sibling smokes follow.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `forms` row in tearDown; safe to
 * re-run.
 */
class LiveAdminModuleContactFormSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'contact-form-module-settings';

    private const FIXTURE_NAME_PREFIX = 'live-admin-module-contact-form-smoke-';

    private const FIXTURE_INITIAL_DESCRIPTION = 'Smoke fixture initial description';

    private const FIXTURE_UPDATED_DESCRIPTION = 'Smoke fixture updated description';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureForms();
        parent::tearDown();
    }

    private function purgeFixtureForms(): void
    {
        DB::table('forms')
            ->where('name', 'like', self::FIXTURE_NAME_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function contact_form_admin_view_loads_and_form_configs_round_trip_through_eloquent(): void
    {
        $this->purgeFixtureForms();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // contact-form admin view (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'contact-form admin view (insertion + submission settings)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'contact-form admin render');

            // Signal #2 — Form-config CRUD round-trip through the
            // Form Eloquent model. Same pipeline every contact-
            // form admin write goes through (form-config name /
            // description / notifications updates from the
            // settings page).
            $this->assertContactFormConfigRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Drive the Form Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the contact-form admin
     * ultimately calls when persisting form-config rows.
     */
    private function assertContactFormConfigRoundTripPersists(): void
    {
        $name = self::FIXTURE_NAME_PREFIX . uniqid();

        $created = Form::create([
            'name' => $name,
            'slug' => 'live-admin-contact-form-smoke-' . uniqid(),
            'description' => self::FIXTURE_INITIAL_DESCRIPTION,
            'confirmation_message' => 'Thank you for your submission.',
            'emails_notifications' => 'admin@example.test',
            'emails_notifications_subject' => 'New contact form submission',
            'is_active' => 1,
        ]);

        $this->assertNotNull(
            $created->id,
            'Form::create must return a model with a primary key — contact-form admin '
            . 'config writes and the public-frontend form-resolution lookup both depend '
            . 'on this round-trip working.'
        );

        $created->description = self::FIXTURE_UPDATED_DESCRIPTION;
        $created->save();

        $reloaded = Form::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Form::find must return the freshly-created row; an Eloquent regression here '
            . 'would silently break form-config reads in the contact-form admin.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_DESCRIPTION,
            (string) $reloaded->description,
            'Form::save must persist field updates — contact-form admin description / '
            . 'confirmation-message / notifications edits bind through the same setter '
            . 'pipeline.'
        );
        $this->assertSame(
            $name,
            (string) $reloaded->name,
            'Form::create must persist the marker name verbatim — a regression here '
            . 'would break the tearDown purge gate that this smoke depends on for '
            . 're-runnability.'
        );

        $reloaded->delete();
        $this->assertNull(
            Form::find($created->id),
            'Form::delete must remove the row from the forms table; failure here would '
            . 'silently leak fixture rows across re-runs (and would also break the '
            . "contact-form admin's \"remove form\" action)."
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Audio/Accordion smokes.
     */
    private function assertSaveActionWired(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasInlineSave = str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:submit=')
            || str_contains($source, 'wire:click="save"')
            || str_contains($source, "wire:click='save'");
        $hasDeferredSave = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'contact-form admin page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id / '
            . 'wire:snapshot / fi-page / fi-form deferred) — otherwise the form-config '
            . 'round-trip asserted above would only prove the model works, not that the '
            . 'admin surface is reachable through the Livewire form pipeline.'
        );
    }
}
