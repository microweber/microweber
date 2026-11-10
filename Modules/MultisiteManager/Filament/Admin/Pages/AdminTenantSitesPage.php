<?php

declare(strict_types=1);

namespace Modules\MultisiteManager\Filament\Admin\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Modules\MultisiteManager\Services\TenantSiteProvisioner;
use Modules\MultisiteManager\Support\TenantManagement;

/**
 * Admin page to manage tenant subsites.
 *
 * Lists the main website (identified via env) plus every configured subsite and
 * exposes create / edit / install / delete actions that drive the
 * {@see TenantSiteProvisioner}. Only reachable by a super-admin on the MAIN site
 * when tenant management is enabled via env — gated three ways in canAccess().
 */
class AdminTenantSitesPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 90;

    // Nest Subsites under the "Settings" item rather than floating beside it in
    // the (header-hidden) Settings group — it reads as part of the Settings section.
    protected static ?string $navigationParentItem = 'Settings';

    protected static ?string $title = 'Subsites';

    protected static ?string $navigationLabel = 'Subsites';

    protected static ?string $slug = 'multisite-manager';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $description = 'Manage the tenant subsites served from this install.';

    protected string $view = 'modules.multisite_manager::filament.pages.admin-tenant-sites';

    /**
     * Three gates: tenant management must be enabled via env, the viewer must be
     * an admin, and management only happens on the MAIN site (never from inside a
     * subsite environment).
     */
    public static function canAccess(): bool
    {
        // Enabled via env AND main site only (availableHere) — then admin auth.
        if (!TenantManagement::availableHere()) {
            return false;
        }

        return function_exists('is_admin') ? (bool) is_admin() : (bool) (auth()->user()?->isAdmin());
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    protected function provisioner(): TenantSiteProvisioner
    {
        return app(TenantSiteProvisioner::class);
    }

    /** Rows for the view: main site first, then subsites. */
    public function getSites(): array
    {
        return $this->provisioner()->all();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createSite')
                ->label('New subsite')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading('Provision a new subsite')
                ->modalSubmitActionLabel('Create subsite')
                ->form($this->siteFormSchema(isCreate: true))
                ->action(fn (array $data) => $this->runCreate($data)),
        ];
    }

    // ── Per-row actions (mounted from the view with a {domain} argument) ──────

    public function editSiteAction(): Action
    {
        return Action::make('editSite')
            ->label('Edit')
            ->icon('heroicon-o-pencil-square')
            ->modalHeading(fn (array $arguments) => 'Edit ' . ($arguments['domain'] ?? 'subsite'))
            ->fillForm(fn (array $arguments) => $this->editDefaults((string) ($arguments['domain'] ?? '')))
            ->form($this->siteFormSchema(isCreate: false))
            ->action(function (array $data, array $arguments) {
                $res = $this->provisioner()->update((string) ($arguments['domain'] ?? ''), $data);
                $this->notify($res);
            });
    }

    public function installSiteAction(): Action
    {
        return Action::make('installSite')
            ->label('Create DB + install')
            ->icon('heroicon-o-circle-stack')
            ->requiresConfirmation()
            ->modalHeading(fn (array $arguments) => 'Install ' . ($arguments['domain'] ?? 'subsite'))
            ->modalDescription('Creates the database if missing and runs the Microweber installer for this subsite. This can take a minute.')
            ->form([
                TextInput::make('admin_email')->label('Admin email')->default('admin@admin.com'),
                TextInput::make('admin_username')->label('Admin username')->default('admin'),
                TextInput::make('admin_password')->label('Admin password')->password()->revealable()->default('admin'),
                Toggle::make('default_content')->label('Install demo content')->default(false),
            ])
            ->action(function (array $data, array $arguments) {
                $domain = (string) ($arguments['domain'] ?? '');
                $info = $this->provisioner()->describe($domain);
                $db = [
                    'host' => $info['db_host'], 'port' => $info['db_port'] ?? '3306',
                    'name' => $info['db_database'], 'username' => $info['db_username'] ?? 'root',
                    'password' => '', 'prefix' => '',
                ];
                // Reuse the stored password from .env.<domain>.
                $db['password'] = $this->provisioner()->describe($domain)['db_username'] !== ''
                    ? $this->readPassword($domain) : '';
                $create = $this->provisioner()->createDatabase($db);
                $install = $create['ok']
                    ? $this->provisioner()->runInstall($domain, $db, $info['app_url'], $data)
                    : ['ok' => false, 'message' => $create['message']];
                $this->notify(['ok' => $install['ok'], 'messages' => [$create['message'], $install['message']]]);
            });
    }

    public function deleteSiteAction(): Action
    {
        return Action::make('deleteSite')
            ->label('Delete')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading(fn (array $arguments) => 'Delete ' . ($arguments['domain'] ?? 'subsite'))
            ->modalDescription('Removes the subsite marker and .env file. Optionally drop its database and purge its storage. This cannot be undone.')
            ->form([
                Toggle::make('drop_database')->label('Also DROP the database')->default(false),
                Toggle::make('purge_storage')->label('Also purge per-site cache/session storage')->default(false),
            ])
            ->action(function (array $data, array $arguments) {
                $res = $this->provisioner()->delete((string) ($arguments['domain'] ?? ''), $data);
                $this->notify($res);
            });
    }

    // ── Form schema shared by create + edit ───────────────────────────────────

    protected function siteFormSchema(bool $isCreate): array
    {
        $domain = $isCreate
            ? TextInput::make('domain')
                ->label('Domain')
                ->placeholder('shop.example.com')
                ->required()
                ->helperText('The host visitors reach this subsite on. A config marker + .env file are created for it.')
            : TextInput::make('domain')->label('Domain')->disabled()->dehydrated(false);

        $components = [
            $domain,
            TextInput::make('app_url')->label('Site URL')->placeholder('https://shop.example.com')
                ->helperText('Defaults to https://<domain>.'),
            Section::make('Database')
                ->columns(2)
                ->schema([
                    TextInput::make('db_host')->label('Host')->default('127.0.0.1'),
                    TextInput::make('db_port')->label('Port')->default('3306'),
                    TextInput::make('db_database')->label('Database')->helperText('Defaults to mw_<slug>.'),
                    TextInput::make('db_prefix')->label('Table prefix')->placeholder('(none)'),
                    TextInput::make('db_username')->label('Username')->default('root'),
                    TextInput::make('db_password')->label('Password')->password()->revealable(),
                ]),
        ];

        if ($isCreate) {
            $components[] = Toggle::make('create_database')
                ->label('Create the database now')->default(true);
            $components[] = Section::make('Install')
                ->description('Run the Microweber installer for the new subsite (recommended for a one-click working site).')
                ->schema([
                    Toggle::make('run_install')->label('Run installer now')->default(true)->live(),
                    TextInput::make('admin_email')->label('Admin email')->default('admin@admin.com')
                        ->visible(fn ($get) => (bool) $get('run_install')),
                    TextInput::make('admin_username')->label('Admin username')->default('admin')
                        ->visible(fn ($get) => (bool) $get('run_install')),
                    TextInput::make('admin_password')->label('Admin password')->password()->revealable()->default('admin')
                        ->visible(fn ($get) => (bool) $get('run_install')),
                    Select::make('template')->label('Template')->options($this->templateOptions())->native(false)
                        ->visible(fn ($get) => (bool) $get('run_install')),
                    Toggle::make('default_content')->label('Install demo content')->default(false)
                        ->visible(fn ($get) => (bool) $get('run_install')),
                ]);
        }

        return $components;
    }

    protected function editDefaults(string $domain): array
    {
        $info = $this->provisioner()->describe($domain);

        return [
            'domain' => $info['domain'],
            'app_url' => $info['app_url'],
            'db_host' => $info['db_host'],
            'db_port' => $info['db_port'] ?? '3306',
            'db_database' => $info['db_database'],
            'db_username' => $info['db_username'] ?? '',
            'db_prefix' => '',
            'db_password' => $this->readPassword($domain),
        ];
    }

    protected function readPassword(string $domain): string
    {
        // describe() intentionally omits the password; read it straight for edit prefill.
        $info = $this->provisioner()->describe($domain);
        $envFile = $info['env_file'] ?? null;
        if (!$envFile || !is_file($envFile)) {
            return '';
        }
        $values = app(\MicroweberPackages\EnvWriter\EnvWriterService::class)->read($envFile);

        return (string) ($values['DB_PASSWORD'] ?? '');
    }

    protected function templateOptions(): array
    {
        $dir = base_path('Templates');
        $out = [];
        foreach (glob($dir . '/*', GLOB_ONLYDIR) ?: [] as $path) {
            $name = basename($path);
            $out[strtolower($name)] = $name;
        }
        asort($out);

        return $out ?: ['bootstrap' => 'Bootstrap'];
    }

    /** Turn a provisioner result into a Filament notification. */
    protected function notify(array $res): void
    {
        $body = implode("\n", array_filter((array) ($res['messages'] ?? [])));
        $n = Notification::make()->title($res['ok'] ? 'Done' : 'There was a problem')->body($body);
        $res['ok'] ? $n->success() : $n->danger();
        $n->send();
    }

    protected function runCreate(array $data): void
    {
        $res = $this->provisioner()->create($data);
        $this->notify($res);
    }
}
