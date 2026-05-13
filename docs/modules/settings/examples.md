# Settings Module — Examples

## Recipe 1: Toggle maintenance mode from a script

```php
$enable = true;

save_option('maintenance_mode', $enable ? '1' : '0', 'website');
save_option('maintenance_message', 'Back in 30 minutes', 'website');

if ($enable) {
    save_option('maintenance_allowed_ips', $_SERVER['REMOTE_ADDR'], 'website');
}
```

The middleware reads `maintenance_mode` on every request and returns a 503 for non-allowed-IP traffic when the flag is `1`.

## Recipe 2: Bulk-configure SMTP for a new deploy

```php
$config = [
    'email_smtp_server'   => 'smtp.mailgun.org',
    'email_smtp_port'     => '587',
    'email_smtp_user'     => 'postmaster@yourdomain.com',
    'email_smtp_password' => env('SMTP_PASSWORD'),
    'email_smtp_encryption' => 'tls',
    'email_from'          => 'noreply@yourdomain.com',
    'email_from_name'     => 'Your Brand',
];

foreach ($config as $key => $value) {
    save_option($key, $value, 'email');
}

// Verify
\Mail::raw('SMTP test', fn ($m) => $m->to('me@example.com')->subject('SMTP test'));
```

## Recipe 3: Per-locale site title

```php
save_option('website_title', 'My Awesome Site', 'website', 'en');
save_option('website_title', 'Mi Sitio Increíble', 'website', 'es');
save_option('website_title', 'Mein tolles Website', 'website', 'de');

// Public page renders the right title automatically based on request locale
app()->setLocale('es');
echo get_option('website_title', 'website');  // "Mi Sitio Increíble"
```

## Recipe 4: Listen for option changes (audit log)

```php
// In a ServiceProvider boot()
\MicroweberPackages\Option\Models\Option::saved(function ($option) {
    if ($option->is_system) return;  // skip system-managed options

    \DB::table('audit_log')->insert([
        'event'         => 'option.saved',
        'option_key'    => $option->option_key,
        'option_group'  => $option->option_group,
        'option_value'  => $option->option_value,
        'user_id'       => auth()->id(),
        'created_at'    => now(),
    ]);
});
```

## Recipe 5: Read multiple options at once for a settings page

```php
$emailConfig = get_options(['option_group' => 'email']);

// Returns:
// [
//   'email_smtp_server' => 'smtp.mailgun.org',
//   'email_smtp_port' => '587',
//   ...
// ]

// Use in a Filament form's mount():
$this->form->fill($emailConfig);
```

## Recipe 6: Custom settings page for a new module

```php
namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;

class CustomAnalyticsSettings extends Page
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Analytics';
    protected string $view = 'filament.pages.custom-analytics-settings';
    public ?array $data = [];

    public function mount(): void
    {
        $this->data = get_options(['option_group' => 'analytics']) ?: [];
        $this->form->fill($this->data);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('ga_measurement_id')->label('GA4 Measurement ID'),
            TextInput::make('gtm_container_id')->label('GTM Container ID'),
            Toggle::make('analytics_enabled')->label('Enable tracking'),
        ];
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            save_option($key, (string) $value, 'analytics');
        }
        \Filament\Notifications\Notification::make()
            ->title('Saved')
            ->success()
            ->send();
    }
}
```

## Recipe 7: Encrypted option storage

If you need encrypted secrets at rest:

```php
namespace App\Models;

use MicroweberPackages\Option\Models\Option as BaseOption;

class EncryptedOption extends BaseOption
{
    protected $casts = [
        'option_value' => 'encrypted',
    ];
}

// Bind for a specific group
$option = EncryptedOption::firstOrNew([
    'option_key' => 'stripe_secret_key',
    'option_group' => 'payment',
]);
$option->option_value = $secret;
$option->save();

// Reads automatically decrypt
$key = EncryptedOption::where('option_key', 'stripe_secret_key')->value('option_value');
```

For production secrets, prefer `.env` over the Option store entirely.

## Recipe 8: Reset options to defaults

```php
// Delete user-edited options (system-managed rows stay)
\MicroweberPackages\Option\Models\Option::where('is_system', 0)->delete();

// Or selectively, by group
\MicroweberPackages\Option\Models\Option::where('option_group', 'shop')
    ->where('is_system', 0)
    ->delete();

// Re-run the install seeder to recreate defaults
\Artisan::call('microweber:install', ['--seed-options' => true]);
```

## Recipe 9: Export all options as JSON

```php
$opts = \MicroweberPackages\Option\Models\Option::orderBy('option_group')->orderBy('option_key')->get();

$grouped = $opts->groupBy('option_group')->map(function ($g) {
    return $g->mapWithKeys(fn ($o) => [$o->option_key => [
        'value' => $o->option_value,
        'lang' => $o->lang,
        'module' => $o->module,
        'is_system' => $o->is_system,
    ]]);
});

file_put_contents(
    storage_path('app/options-export-' . now()->format('Y-m-d') . '.json'),
    json_encode($grouped, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);
```

Useful for site migrations + config audits.

## Recipe 10: REST API — read site title from a JS widget

```javascript
fetch('/api/settings/website_title?option_group=website')
    .then(r => r.json())
    .then(data => {
        document.querySelector('h1.site-title').textContent = data.option_value;
    });
```

Note: this endpoint is public for the `website` group. Sensitive groups (`email`, `system`) require an admin bearer token.
