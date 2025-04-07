<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            // Debug current URL and page
            $browser->visit('/admin/login')
                   ->pause(5000)
                   ->screenshot('login-attempt')
                   ->dump();
            $currentUrl = $browser->driver->getCurrentURL();
            file_put_contents(storage_path('login-url.txt'), $currentUrl);
            $pageSource = $browser->driver->getPageSource();
            file_put_contents(storage_path('login-page.html'), $pageSource);
            
            // Basic verification
            if (str_contains($pageSource, '<html')) {
                file_put_contents(storage_path('login-debug.log'), 'Page contains HTML tag');
            }
            // Verify form exists
            $browser->waitFor('form', 10);
            
            // Debug form inputs
            $inputs = $browser->script("return Array.from(document.querySelectorAll('form input')).map(i => i.name)");
            file_put_contents(storage_path('form-inputs.log'), print_r($inputs, true));
            
            // Try multiple possible email field selectors
            try {
                $browser->type('input[name="email"]', 'test@example.com');
            } catch (\Exception $e) {
                $browser->type('input[type="email"]', 'test@example.com');
            }
            
            // Verify credentials exist
            $userExists = $browser->script("return fetch('/api/check-user?email=test@example.com').then(r => r.ok)");
            file_put_contents(storage_path('user-check.log'), print_r($userExists, true));

            // Debug form structure
            $formHtml = $browser->script("return document.querySelector('form').outerHTML")[0];
            file_put_contents(storage_path('form-structure.log'), $formHtml);

            // Get CSRF token from meta tag
            $token = $browser->script("return document.querySelector('meta[name=\"csrf-token\"]').content")[0];
            if ($token) {
                // Try to set token in form if input exists
                $tokenInputExists = $browser->script("return !!document.querySelector('input[name=\"_token\"]')")[0];
                if ($tokenInputExists) {
                    $browser->script("document.querySelector('input[name=\"_token\"]').value = '$token'");
                }
            } else {
                file_put_contents(storage_path('csrf-error.log'), 'CSRF token not found');
            }

            // Verify test user exists
            $userExists = \DB::table('users')->where('email', 'test@example.com')->exists();
            file_put_contents(storage_path('user-exists.log'), $userExists ? 'User exists' : 'User not found');

            $browser->type('input[type="password"]', 'password')
                   ->click('button.fi-btn-primary, button[type="submit"].fi-btn')
                   ->pause(3000) // Wait for potential redirect
                   ->screenshot('post-login') // Debug post-login state
                   ->assertPathIs('/admin') // Verify successful redirect
                   ->screenshot('admin-dashboard'); // Debug dashboard state
        });
    }
}
