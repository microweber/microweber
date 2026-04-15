<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Personal Access Tokens Section --}}
        <x-filament::section>
            <x-slot name="heading">Personal Access Tokens</x-slot>
            <x-slot name="description">Create tokens to authenticate API requests. Tokens grant access to the API on your behalf.</x-slot>

            <div class="space-y-4">
                {{-- Create Token Form --}}
                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Token Name</label>
                        <input type="text" wire:model="newTokenName" placeholder="e.g. My API Client"
                            class="fi-input block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    </div>
                    <x-filament::button wire:click="createPersonalToken" icon="heroicon-o-plus">
                        Create Token
                    </x-filament::button>
                </div>

                {{-- Newly Created Token --}}
                @if($newTokenValue)
                    <div class="rounded-lg border border-yellow-300 bg-yellow-50 p-4 dark:border-yellow-600 dark:bg-yellow-900/20">
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-yellow-500 mt-0.5 shrink-0" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Copy this token now — it will not be shown again:</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <code class="block flex-1 rounded bg-gray-900 px-3 py-2 text-sm text-green-400 font-mono break-all select-all">{{ $newTokenValue }}</code>
                                </div>
                                <button wire:click="dismissTokenValue" class="mt-2 text-xs text-yellow-700 dark:text-yellow-300 underline hover:no-underline">Dismiss</button>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Existing Tokens Table --}}
                @if(count($personalTokens) > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Scopes</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Created</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Expires</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                                @foreach($personalTokens as $token)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $token['name'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $token['scopes'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $token['created_at'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $token['expires_at'] }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <x-filament::button size="sm" color="danger" wire:click="revokeToken('{{ $token['id'] }}')" icon="heroicon-o-trash">
                                                Revoke
                                            </x-filament::button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">No personal access tokens created yet.</p>
                @endif
            </div>
        </x-filament::section>

        {{-- OAuth Applications Section --}}
        <x-filament::section>
            <x-slot name="heading">OAuth Applications</x-slot>
            <x-slot name="description">Register OAuth applications to allow third-party services to authenticate via OAuth2.</x-slot>

            <div class="space-y-4">
                {{-- Create Application Form --}}
                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Application Name</label>
                        <input type="text" wire:model="newAppName" placeholder="e.g. My External App"
                            class="fi-input block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Redirect URL</label>
                        <input type="text" wire:model="newAppRedirect" placeholder="https://example.com/callback"
                            class="fi-input block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    </div>
                    <x-filament::button wire:click="createApplication" icon="heroicon-o-plus">
                        Create Application
                    </x-filament::button>
                </div>

                {{-- Newly Created Client Secret --}}
                @if($newClientSecret)
                    <div class="rounded-lg border border-yellow-300 bg-yellow-50 p-4 dark:border-yellow-600 dark:bg-yellow-900/20">
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-yellow-500 mt-0.5 shrink-0" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Copy these credentials now — the secret will not be shown again:</p>
                                <div class="mt-2 space-y-2">
                                    <div>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Client ID:</span>
                                        <code class="block rounded bg-gray-900 px-3 py-2 text-sm text-green-400 font-mono break-all select-all">{{ $newClientId }}</code>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Client Secret:</span>
                                        <code class="block rounded bg-gray-900 px-3 py-2 text-sm text-green-400 font-mono break-all select-all">{{ $newClientSecret }}</code>
                                    </div>
                                </div>
                                <button wire:click="dismissClientSecret" class="mt-2 text-xs text-yellow-700 dark:text-yellow-300 underline hover:no-underline">Dismiss</button>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Existing Applications Table --}}
                @if(count($applications) > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Client ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Redirect</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Created</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                                @foreach($applications as $app)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $app['name'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono">{{ Str::limit($app['id'], 16) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $app['redirect_uris'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $app['created_at'] }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <x-filament::button size="sm" color="danger" wire:click="revokeApplication('{{ $app['id'] }}')" icon="heroicon-o-trash">
                                                Revoke
                                            </x-filament::button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">No OAuth applications created yet.</p>
                @endif
            </div>
        </x-filament::section>

        {{-- API Documentation Section --}}
        <x-filament::section collapsible collapsed>
            <x-slot name="heading">API Usage Guide</x-slot>

            <div class="prose dark:prose-invert max-w-none text-sm">
                <h4>Authentication with Personal Access Token</h4>
                <p>Include the token in the <code>Authorization</code> header:</p>
                <pre class="bg-gray-900 text-green-400 rounded-lg p-4"><code>curl -X GET "{{ config('app.url') }}/api/user" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"</code></pre>

                <h4>OAuth2 Authorization Code Flow</h4>
                <p>1. Redirect users to authorize your application:</p>
                <pre class="bg-gray-900 text-green-400 rounded-lg p-4"><code>GET {{ config('app.url') }}/oauth/authorize?client_id=CLIENT_ID&redirect_uri=REDIRECT_URI&response_type=code&scope=*</code></pre>
                <p>2. Exchange the authorization code for an access token:</p>
                <pre class="bg-gray-900 text-green-400 rounded-lg p-4"><code>POST {{ config('app.url') }}/oauth/token
{
    "grant_type": "authorization_code",
    "client_id": "CLIENT_ID",
    "client_secret": "CLIENT_SECRET",
    "redirect_uri": "REDIRECT_URI",
    "code": "AUTHORIZATION_CODE"
}</code></pre>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>
