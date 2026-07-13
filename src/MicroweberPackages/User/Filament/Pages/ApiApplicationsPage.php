<?php

namespace MicroweberPackages\User\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;

class ApiApplicationsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $title = 'API Applications';
    protected static ?string $slug = 'api-applications';
    protected static string|\UnitEnum|null $navigationGroup = 'System Settings';
    protected static ?int $navigationSort = 2500;

    // task-2026-05-23-cc3b22 / AI-1053 — description for settings hub card.
    public static string $description = 'Manage OAuth API keys, personal access tokens, and third-party application access.';

    protected string $view = 'user::filament.pages.api-applications';

    public array $applications = [];
    public array $personalTokens = [];
    public array $newTokenScopes = [];
    public array $availableScopes = [];
    public ?string $newTokenValue = null;
    public ?string $newClientSecret = null;
    public ?string $newClientId = null;

    public static function canAccess(): bool
    {
        return is_admin();
    }

    public function mount(): void
    {
        $this->loadApplications();
        $this->loadPersonalTokens();
        try {
            $this->availableScopes = Passport::scopes()
                ->mapWithKeys(fn ($scope) => [$scope->id => $scope->description])
                ->toArray();
        } catch (\Throwable $e) {
            $this->availableScopes = [];
        }
    }

    protected function loadApplications(): void
    {
        try {
            $user = auth()->user();
            $clients = Client::where('owner_id', $user->id)
                ->where('owner_type', get_class($user))
                ->whereJsonDoesntContain('grant_types', 'personal_access')
                ->where('revoked', false)
                ->orderBy('created_at', 'desc')
                ->get();

            $this->applications = $clients->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'redirect_uris' => $client->redirect_uris ? implode(', ', $client->redirect_uris) : '',
                    'created_at' => $client->created_at->format('Y-m-d H:i'),
                ];
            })->toArray();
        } catch (\Throwable $e) {
            // Passport tables may not exist yet (migration not run)
            $this->applications = [];
        }
    }

    protected function loadPersonalTokens(): void
    {
        try {
            $this->loadPersonalTokensInner();
        } catch (\Throwable $e) {
            // Passport tables may not exist yet (migration not run)
            $this->personalTokens = [];
        }
    }

    protected function loadPersonalTokensInner(): void
    {
        $user = auth()->user();
        $tokens = $user->tokens()
            ->where('revoked', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->personalTokens = $tokens->map(function ($token) {
            // `last_used_at` is stamped by the `token.audit` middleware
            // after each authenticated /api/module/* request. Fall through
            // to the Eloquent attribute so tokens that have never been
            // used display "Never" instead of "updated_at" (which is the
            // revocation timestamp and was misleading in practice).
            $lastUsedAt = $token->last_used_at ?? null;
            if (is_string($lastUsedAt)) {
                $lastUsedAt = \Carbon\Carbon::parse($lastUsedAt);
            }

            return [
                'id' => $token->id,
                // task-2026-05-22-9d1964 / AI-916 — read $token->name (the user-assigned
                // label set via createToken('My name')) not $token->client->name (the
                // system-level OAuth client name "Personal Access Client" which is
                // implementation detail, not user-meaningful). Pre-fix: NAME column
                // showed blank because PAC client name is usually null or generic.
                'name' => $token->name ?? 'Personal Access Token',
                'scopes' => $token->scopes ? implode(', ', $token->scopes) : 'All',
                'created_at' => $token->created_at->format('Y-m-d H:i'),
                'last_used_at' => $lastUsedAt?->format('Y-m-d H:i') ?? 'Never',
                'last_used_ip' => $token->last_used_ip ?? null,
                'expires_at' => $token->expires_at?->format('Y-m-d H:i') ?? 'Never',
            ];
        })->toArray();
    }

    public function createPersonalToken(): void
    {
        $name = $this->newTokenName ?? 'API Token';
        $user = auth()->user();

        // Empty selection means "full access" — `*` is Passport's wildcard
        // scope and bypasses every scope:<name> middleware check.
        $scopes = array_values(array_filter(
            Passport::validScopes($this->newTokenScopes),
            fn ($s) => $s !== ''
        ));
        if (empty($scopes)) {
            $scopes = ['*'];
        }

        $result = $user->createToken($name, $scopes);
        $this->newTokenValue = $result->accessToken;

        $this->newTokenScopes = [];
        $this->loadPersonalTokens();

        Notification::make()
            ->title('Personal Access Token Created')
            ->body('Copy the token now — it will not be shown again.')
            ->success()
            ->send();
    }

    public function revokeToken(string $tokenId): void
    {
        $user = auth()->user();
        $token = $user->tokens()->where('id', $tokenId)->first();

        if ($token) {
            $token->revoke();

            $refreshToken = \DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $tokenId)
                ->update(['revoked' => true]);
        }

        $this->loadPersonalTokens();

        Notification::make()
            ->title('Token Revoked')
            ->success()
            ->send();
    }

    public function revokeAllPersonalTokens(): int
    {
        $user = auth()->user();

        $tokenIds = $user->tokens()
            ->where('revoked', false)
            ->pluck('id');

        $count = $tokenIds->count();

        if ($count === 0) {
            Notification::make()
                ->title('No active tokens to revoke')
                ->info()
                ->send();

            $this->loadPersonalTokens();

            return 0;
        }

        $user->tokens()
            ->whereIn('id', $tokenIds)
            ->update(['revoked' => true]);

        \DB::table('oauth_refresh_tokens')
            ->whereIn('access_token_id', $tokenIds)
            ->update(['revoked' => true]);

        $this->loadPersonalTokens();

        Notification::make()
            ->title('All Personal Tokens Revoked')
            ->body("Revoked {$count} active token" . ($count === 1 ? '' : 's') . '.')
            ->success()
            ->send();

        return $count;
    }

    public function createApplication(): void
    {
        $name = $this->newAppName ?? 'My Application';
        $redirect = $this->newAppRedirect ?? config('app.url') . '/callback';

        $user = auth()->user();

        $secret = Str::random(40);

        $client = Passport::client()->forceFill([
            'owner_id' => $user->id,
            'owner_type' => get_class($user),
            'name' => $name,
            // Let the model's `secret` Attribute mutator handle hashing —
            // do NOT pre-hash with sha256 or it double-hashes and the
            // plain-text secret the user copies will never verify.
            'secret' => $secret,
            // The model casts redirect_uris and grant_types to 'array',
            // so pass plain arrays — json_encode would double-encode.
            'redirect_uris' => [$redirect],
            'revoked' => false,
            'grant_types' => ['authorization_code', 'refresh_token'],
        ]);
        $client->save();

        $this->newClientId = $client->id;
        // Show the raw secret the user should copy (Passport stores a
        // hashed version; this plain text cannot be recovered later).
        $this->newClientSecret = $secret;

        $this->loadApplications();

        Notification::make()
            ->title('OAuth Application Created')
            ->body('Copy the client secret now — it will not be shown again.')
            ->success()
            ->send();
    }

    public function revokeApplication(string $clientId): void
    {
        $user = auth()->user();
        $client = Client::where('id', $clientId)
            ->where('owner_id', $user->id)
            ->where('owner_type', get_class($user))
            ->first();

        if ($client) {
            $client->tokens()->update(['revoked' => true]);
            $client->update(['revoked' => true]);
        }

        $this->newClientSecret = null;
        $this->newClientId = null;
        $this->loadApplications();

        Notification::make()
            ->title('Application Revoked')
            ->success()
            ->send();
    }

    public function dismissTokenValue(): void
    {
        $this->newTokenValue = null;
    }

    public function dismissClientSecret(): void
    {
        $this->newClientSecret = null;
        $this->newClientId = null;
    }

    public string $newTokenName = '';
    public string $newAppName = '';
    public string $newAppRedirect = '';
}
