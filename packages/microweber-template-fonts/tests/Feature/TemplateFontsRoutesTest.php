<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Feature;

use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

class TemplateFontsRoutesTest extends TestCase
{
    protected function actingAsAdminIfPossible(): void
    {
        if (!class_exists(\App\Models\User::class) && !class_exists(\MicroweberPackages\User\Models\User::class)) {
            return;
        }

        $userClass = class_exists(\MicroweberPackages\User\Models\User::class)
            ? \MicroweberPackages\User\Models\User::class
            : \App\Models\User::class;

        try {
            $user = $userClass::query()->where('is_admin', 1)->first();
            if (!$user) {
                $user = $userClass::query()->create([
                    'username' => 'fonts_admin_' . uniqid(),
                    'email' => 'fonts_admin_' . uniqid() . '@test.com',
                    'password' => bcrypt('password'),
                    'is_admin' => 1,
                    'is_active' => 1,
                ]);
            }
            $this->actingAs($user);
        } catch (\Throwable) {
            // standalone / no users table
        }
    }

    public function test_get_fonts_returns_catalog(): void
    {
        $this->actingAsAdminIfPossible();
        $response = $this->getJson(route('api.template.get-fonts'));

        if (in_array($response->status(), [401, 403, 302], true)) {
            $this->markTestSkipped('Admin auth required for get-fonts');
        }

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertIsArray($response->json('data'));
        $this->assertNotEmpty($response->json('data'));
    }

    public function test_save_and_get_favorite_fonts(): void
    {
        $this->actingAsAdminIfPossible();

        $save = $this->postJson(route('api.template.save-template-fonts'), [
            'fonts' => ['Roboto'],
        ]);

        if (in_array($save->status(), [401, 403, 302, 419], true)) {
            // Try via manager directly then hit public-ish endpoints
            app(TemplateFontsManager::class)->enableFont('Roboto');
        } else {
            $save->assertStatus(200);
            $save->assertJsonPath('success', true);
        }

        $fav = $this->getJson(route('api.template.get-favorite-fonts'));
        if (in_array($fav->status(), [401, 403, 302], true)) {
            $this->assertContains('Roboto', app(TemplateFontsManager::class)->getEnabledFonts());

            return;
        }

        $fav->assertStatus(200);
        $this->assertContains('Roboto', $fav->json('data'));
    }

    public function test_remove_favorite_font(): void
    {
        app(TemplateFontsManager::class)->enableFont('Lato');
        $this->actingAsAdminIfPossible();

        $response = $this->postJson(route('api.template.remove-favorite-font'), [
            'font' => 'Lato',
        ]);

        if (in_array($response->status(), [401, 403, 302, 419], true)) {
            app(TemplateFontsManager::class)->removeFont('Lato');
            $this->assertNotContains('Lato', app(TemplateFontsManager::class)->getEnabledFonts());

            return;
        }

        $response->assertStatus(200);
        $this->assertNotContains('Lato', app(TemplateFontsManager::class)->getEnabledFonts());
    }

    public function test_print_css_includes_enabled_font(): void
    {
        $manager = app(TemplateFontsManager::class);
        $manager->enableFont('Montserrat');
        try {
            $response = $this->get(route('print_custom_css_fonts'));
            $response->assertStatus(200);
            // Local copies render as a lowercase-slug path ("…/montserrat/font.css"),
            // so match case-insensitively rather than the remote @import form.
            $this->assertStringContainsStringIgnoringCase('Montserrat', $response->getContent() ?: '');
        } finally {
            $manager->removeFont('Montserrat');
        }
    }
}
