<?php

namespace Modules\Ai\Filament\Pages;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use Modules\Ai\Services\Secrets\PassSecretStore;

class AiSettingsPage extends AdminSettingsPage
{
protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
protected static bool $shouldRegisterNavigation = true;

protected string $view = 'modules.settings::filament.admin.pages.settings-form';

protected static ?string $title = 'AI Settings';

protected static string $description = 'Configure your AI provider settings and API keys';

protected static string | \UnitEnum | null $navigationGroup = 'System Settings';
protected static ?int $navigationSort = 3000;

public array $optionGroups = [
'ai'
];

    protected array $secretOptionKeys = [
        'openai_api_key',
        'gemini_api_key',
        'openrouter_api_key',
        'anthropic_api_key',
        'replicate_api_key',
        'supadata_api_key',
        'tavily_api_key',
        'fal_api_key',
        'ollama_api_key',
    ];

public static function shouldRegisterNavigation(): bool
{
$isDisabled = config('modules.ai.disable_settings', false);

if ($isDisabled) {
return false;
}

return static::$shouldRegisterNavigation;
}

public static function canAccess(): bool
{
return is_admin();
}

public function updated($propertyName, $value): void
{
        $secretOptionKey = $this->extractSecretOptionKey($propertyName);

        if ($secretOptionKey !== null) {
            $this->storeSecretOption($secretOptionKey, $value);
            Artisan::call('config:clear');

            return;
        }

        parent::updated($propertyName, $value);

        // Clear config cache when AI settings are updated
        if (str_starts_with($propertyName, 'options.ai.')) {
            Artisan::call('config:clear');
        }
}

    public function mount()
    {
        parent::mount();

        foreach ($this->secretOptionKeys as $secretOptionKey) {
            if (!empty($this->options['ai'][$secretOptionKey] ?? null)) {
                $this->options['ai'][$secretOptionKey] = '';
            }
        }
    }



    public function form(Schema $schema): Schema
    {

        $isDisabled = config('modules.ai.disable_settings', false);

        if($isDisabled){
            return $schema
                ->schema([
                    Section::make('AI settings are disabled')
                        ->icon('heroicon-m-x-circle')
                        ->view('mw-filament::sections.section')
                        ->schema([
                            Placeholder::make('options.ai.disabled_message')
                                ->label('AI settings are currently disabled')
                                ->disabled()
                                ->helperText('AI settings features are not available at this time.'),
                        ])
            ]);

        }





        return $schema
            ->schema([
Section::make('General AI Settings')
->icon('heroicon-m-sparkles')
->view('mw-filament::sections.section')
->schema([
Toggle::make('options.ai.enabled')
->label('Enable AI Functionality')
->live()
->onIcon('heroicon-m-check')
->offIcon('heroicon-m-x-mark')
->helperText('Enable or disable all AI features globally'),

// task-2026-05-23-0b73f1 / AI-1059 — show hint when AI is disabled explaining that
// enabling it reveals the provider/API-key configuration sections below.
// The toggle already has ->live() so sections appear immediately on toggle.
Placeholder::make('ai_disabled_hint')
    ->hiddenLabel()
    ->hidden(fn(callable $get) => (bool) $get('options.ai.enabled'))
    ->content(new \Illuminate\Support\HtmlString('<p style="color:#6b7280;font-size:13px;margin:0;">Enable AI Functionality above to configure providers (OpenAI, Gemini, Ollama, etc.) and API keys.</p>')),

Toggle::make('options.ai.debug_mode')
->visible(fn(callable $get) => $get('options.ai.enabled'))
->label('Debug Mode')
->live()
->onIcon('heroicon-m-check')
->offIcon('heroicon-m-x-mark')
->helperText('Enable debug mode to log AI requests and responses for troubleshooting'),

Select::make('options.ai.default_driver')
->visible(fn(callable $get) => $get('options.ai.enabled'))
->label('Set default AI provider for text generation')
->live()
->options([
'openai' => 'OpenAI',
'gemini' => 'Google Gemini',
'openrouter' => 'OpenRouter',
'ollama' => 'Ollama',
'supadata' => 'Supadata',
])
->helperText('Select the provider to use for AI text generation tasks'),

Select::make('options.ai.default_driver_images')
->visible(fn(callable $get) => $get('options.ai.enabled'))
->label('Set default AI provider for image generation')
->live()
->options([
// 'gemini' => 'Google Gemini',
// 'openai' => 'OpenAI (DALL-E)',
'replicate' => 'Replicate',
'fal' => 'FAL AI',
])
->helperText('Select the provider to use for AI image generation tasks')
]),

                Section::make('OpenAI Settings')
                    ->icon('heroicon-m-cpu-chip')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('mw-filament::sections.section')
                    ->schema([
                        Toggle::make('options.ai.openai_enabled')
                            ->label('Enable OpenAI')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')

                        ,

                        TextInput::make('options.ai.openai_model')
                            ->live(onBlur: true)
                            ->label('OpenAI Model')
                            ->visible(fn(callable $get) => $get('options.ai.openai_enabled'))
                            ->placeholder('gpt-4o-mini')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Type your model name (e.g. gpt-4o-mini, gpt-4o, gpt-4-turbo). <a style="text-decoration: underline; color: #2563eb;" href="https://platform.openai.com/docs/models" target="_blank">Browse available models</a>.</small>')),

                        TextInput::make('options.ai.openai_api_key')
                            ->live(onBlur: true)
                            ->password()
                            ->revealable()
                            ->label('OpenAI API Key')
                            ->visible(fn(callable $get) => $get('options.ai.openai_enabled'))
                            ->placeholder('Enter your OpenAI API key')
                            ->helperText(fn() => $this->secretHelperText('openai_api_key', '<small class="mb-2 text-muted"><a style="text-decoration: underline; color: #2563eb;" href="https://platform.openai.com/signup" target="_blank">Sign up</a> for an OpenAI account to get your API key.</small>')),

                        TextInput::make('options.ai.openai_base_url')
                            ->live(onBlur: true)
                            ->label('OpenAI Base URL')
                            ->visible(fn(callable $get) => $get('options.ai.openai_enabled'))
                            ->placeholder('https://api.openai.com/v1')
                            ->helperText('Optional. Set a custom base URL to use OpenAI-compatible APIs (e.g. Azure OpenAI, local LLMs, LiteLLM proxy).'),

                        $this->testConnectionButton('openai')
                            ->visible(fn(callable $get) => $get('options.ai.openai_enabled')),
                    ]),

                Section::make('Google Gemini Settings')
                    ->icon('heroicon-m-cpu-chip')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('mw-filament::sections.section')
                    ->schema([
                        Toggle::make('options.ai.gemini_enabled')
                            ->label('Enable Google Gemini')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')

                        ,

                        TextInput::make('options.ai.gemini_model')
                            ->label('Gemini Model')
                            ->live(onBlur: true)
                            ->visible(fn(callable $get) => $get('options.ai.gemini_enabled'))
                            ->placeholder('gemini-pro')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Type your model name (e.g. gemini-pro, gemini-2.0-flash, gemini-2.5-pro). <a style="text-decoration: underline; color: #2563eb;" href="https://ai.google.dev/models/gemini" target="_blank">Browse available models</a>.</small>')),

                        TextInput::make('options.ai.gemini_api_key')
                            ->live(onBlur: true)
                            ->password()
                            ->revealable()
                            ->visible(fn(callable $get) => $get('options.ai.gemini_enabled'))
                            ->label('Gemini API Key')
                            ->placeholder('Enter your Gemini API key')
                            ->helperText(fn() => $this->secretHelperText('gemini_api_key', '<small class="mb-2 text-muted"><a style="text-decoration: underline; color: #2563eb;" href="https://makersuite.google.com/app/apikey" target="_blank">Get your API key</a> from Google AI Studio.</small>')),

                        $this->testConnectionButton('gemini')
                            ->visible(fn(callable $get) => $get('options.ai.gemini_enabled')),
                    ]),

                Section::make('OpenRouter Settings')
                    ->icon('heroicon-m-cpu-chip')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('mw-filament::sections.section')
                    ->schema([
                        Toggle::make('options.ai.openrouter_enabled')
                            ->label('Enable OpenRouter')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')

                        ,

                        TextInput::make('options.ai.openrouter_model')
                            ->live(onBlur: true)
                            ->visible(fn(callable $get) => $get('options.ai.openrouter_enabled'))
                            ->label('OpenRouter Model')
                            ->placeholder('meta-llama/llama-3.3-70b-instruct')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Type the model identifier (e.g. meta-llama/llama-3.3-70b-instruct, anthropic/claude-3.5-sonnet). <a style="text-decoration: underline; color: #2563eb;" href="https://openrouter.ai/models" target="_blank">Browse available models</a>.</small>')),

                        TextInput::make('options.ai.openrouter_api_key')
                            ->live(onBlur: true)
                            ->password()
                            ->revealable()
                            ->visible(fn(callable $get) => $get('options.ai.openrouter_enabled'))
                            ->label('OpenRouter API Key')
                            ->placeholder('Enter your OpenRouter API key')
                            ->helperText(fn() => $this->secretHelperText('openrouter_api_key', '<small class="mb-2 text-muted"><a style="text-decoration: underline; color: #2563eb;" href="https://openrouter.ai/signup" target="_blank">Sign up</a> for an OpenRouter account.</small>')),

                        $this->testConnectionButton('openrouter')
                            ->visible(fn(callable $get) => $get('options.ai.openrouter_enabled')),
                    ]),

                Section::make('Ollama Settings')
                    ->icon('heroicon-m-server')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('mw-filament::sections.section')
                    ->schema([
                        Toggle::make('options.ai.ollama_enabled')
                            ->label('Enable Ollama')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark'),

                        TextInput::make('options.ai.ollama_model')
                            ->live(onBlur: true)
                            ->visible(fn(callable $get) => $get('options.ai.ollama_enabled'))
                            ->label('Ollama Model')
                            ->placeholder('llama3.2')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Type your model name (e.g. llama3.2, mistral, gemma2, codellama). <a style="text-decoration: underline; color: #2563eb;" href="https://ollama.com/library" target="_blank">Browse available models</a>.</small>')),

                        TextInput::make('options.ai.ollama_api_url')
                            ->live(onBlur: true)
                            ->visible(fn(callable $get) => $get('options.ai.ollama_enabled'))
                            ->label('Ollama API URL')
                            ->placeholder('http://localhost:11434/api/generate')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Enter the URL for your local or remote Ollama instance.</small>')),

                        TextInput::make('options.ai.ollama_api_key')
                            ->live(onBlur: true)
                            ->password()
                            ->revealable()
                            ->visible(fn(callable $get) => $get('options.ai.ollama_enabled'))
                            ->label('API Key (optional)')
                            ->placeholder('Enter API key for remote Ollama')
                            ->helperText(fn() => $this->secretHelperText('ollama_api_key', '<small class="mb-2 text-muted">Optional. Required only for remote Ollama services that need authentication.</small>')),

                        $this->testConnectionButton('ollama')
                            ->visible(fn(callable $get) => $get('options.ai.ollama_enabled')),
                    ]),

                Section::make('Anthropic/Claude Settings')
                    ->icon('heroicon-m-cpu-chip')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('mw-filament::sections.section')
                    ->schema([
                        Toggle::make('options.ai.anthropic_enabled')
                            ->label('Enable Anthropic/Claude')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark'),
                        TextInput::make('options.ai.anthropic_model')
                            ->live(onBlur: true)
                            ->visible(fn(callable $get) => $get('options.ai.anthropic_enabled'))
                            ->label('Anthropic/Claude Model')
                            ->placeholder('claude-sonnet-4-6')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Type your model name (e.g. claude-sonnet-4-6, claude-haiku-4-5-20251001). <a style="text-decoration: underline; color: #2563eb;" href="https://docs.anthropic.com/en/docs/about-claude/models" target="_blank">Browse available models</a>.</small>')),
                        TextInput::make('options.ai.anthropic_api_key')
                            ->live(onBlur: true)
                            ->password()
                            ->revealable()
                            ->visible(fn(callable $get) => $get('options.ai.anthropic_enabled'))
                            ->label('Anthropic API Key')
                            ->placeholder('Enter your Anthropic API key')
                            ->helperText(fn() => $this->secretHelperText('anthropic_api_key', '<small class="mb-2 text-muted">Find your API key in your Anthropic account dashboard.</small>')),

                        $this->testConnectionButton('anthropic')
                            ->visible(fn(callable $get) => $get('options.ai.anthropic_enabled')),
                    ]),

                Section::make('Replicate Settings')
                    ->icon('heroicon-m-cpu-chip')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('mw-filament::sections.section')
                    ->schema([
                        Toggle::make('options.ai.replicate_enabled')
                            ->label('Enable Replicate')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark'),
                        TextInput::make('options.ai.replicate_model')
                            ->live(onBlur: true)
                            ->visible(fn(callable $get) => $get('options.ai.replicate_enabled'))
                            ->label('Image Generation Model')
                            ->placeholder('stabilityai/stable-diffusion-xl-base-1.0')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Type the model identifier (e.g. stabilityai/stable-diffusion-xl-base-1.0). <a style="text-decoration: underline; color: #2563eb;" href="https://replicate.com/collections/text-to-image" target="_blank">Browse available models</a>.</small>')),

                        TextInput::make('options.ai.replicate_api_key')
                            ->live(onBlur: true)
                            ->password()
                            ->revealable()
                            ->visible(fn(callable $get) => $get('options.ai.replicate_enabled'))
                            ->label('Replicate API Token')
                            ->placeholder('Enter your Replicate API token')
                            ->helperText(fn() => $this->secretHelperText('replicate_api_key', '<small class="mb-2 text-muted"><a style="text-decoration: underline; color: #2563eb;" href="https://replicate.com/account/api-tokens" target="_blank">Get your API token</a> from Replicate.</small>')),

                        $this->testConnectionButton('replicate')
                            ->visible(fn(callable $get) => $get('options.ai.replicate_enabled')),
                    ]),

Section::make('Supadata Settings')
->icon('heroicon-m-circle-stack')
->visible(fn(callable $get) => $get('options.ai.enabled'))
->view('mw-filament::sections.section')
->schema([
Toggle::make('options.ai.supadata_enabled')
->label('Enable Supadata')
->live()
->onIcon('heroicon-m-check')
->offIcon('heroicon-m-x-mark')
->helperText('Enable Supadata AI service for text generation'),

TextInput::make('options.ai.supadata_model')
->live(onBlur: true)
->visible(fn(callable $get) => $get('options.ai.supadata_enabled'))
->label('Supadata Model')
->placeholder('supadata-default')
->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Type your model name (e.g. supadata-default, supadata-pro, supadata-turbo). <a style="text-decoration: underline; color: #2563eb;" href="https://supadata.com/" target="_blank">Learn more</a>.</small>')),

TextInput::make('options.ai.supadata_api_key')
->live(onBlur: true)
->password()
->revealable()
->visible(fn(callable $get) => $get('options.ai.supadata_enabled'))
->label('Supadata API Key')
->placeholder('Enter your Supadata API key')
->helperText(fn() => $this->secretHelperText('supadata_api_key', '<small class="mb-2 text-muted"><a style="text-decoration: underline; color: #2563eb;" href="https://supadata.com/api-keys" target="_blank">Get your API key</a> from Supadata dashboard.</small>')),

TextInput::make('options.ai.supadata_api_endpoint')
->live()
->visible(fn(callable $get) => $get('options.ai.supadata_enabled'))
->label('API Endpoint')
->placeholder('https://api.supadata.com')
->helperText('Enter the Supadata API endpoint URL'),

TextInput::make('options.ai.supadata_max_tokens')
->live()
->visible(fn(callable $get) => $get('options.ai.supadata_enabled'))
->label('Max Tokens')
->numeric()
->placeholder('Leave empty for default')
->helperText('Maximum number of tokens to generate'),

TextInput::make('options.ai.supadata_temperature')
->live()
->visible(fn(callable $get) => $get('options.ai.supadata_enabled'))
->label('Temperature')
->numeric()
->step(0.1)
->minValue(0)
->maxValue(2)
->placeholder('0.7')
->helperText('Controls randomness: 0 is deterministic, higher values are more random'),
]),

Section::make('TAVILY Search Settings')
                    ->icon('heroicon-m-magnifying-glass')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('mw-filament::sections.section')
                    ->schema([
                        Toggle::make('options.ai.tavily_enabled')
                            ->label('Enable TAVILY Search')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->helperText('Enable TAVILY for AI-powered web search capabilities'),

                        TextInput::make('options.ai.tavily_api_key')
                            ->live(onBlur: true)
                            ->password()
                            ->revealable()
                            ->visible(fn(callable $get) => $get('options.ai.tavily_enabled'))
                            ->label('TAVILY API Key')
                            ->placeholder('Enter your TAVILY API key')
                            ->helperText(fn() => $this->secretHelperText('tavily_api_key', '<small class="mb-2 text-muted"><a style="text-decoration: underline; color: #2563eb;" href="https://tavily.com/" target="_blank">Sign up</a> for a TAVILY account to get your API key.</small>')),

                        Select::make('options.ai.tavily_search_depth')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.tavily_enabled'))
                            ->label('Search Depth')
                            ->options([
                                'basic' => 'Basic Search',
                                'advanced' => 'Advanced Search'
                            ])
                            ->default('basic')
                            ->helperText('Basic search is faster, Advanced search provides more comprehensive results'),

                        TextInput::make('options.ai.tavily_max_results')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.tavily_enabled'))
                            ->label('Max Results')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(20)
                            ->default(5)
                            ->helperText('Maximum number of search results to return (1-20)'),
]),

Section::make('FAL AI Settings')
                    ->icon('heroicon-m-photo')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('mw-filament::sections.section')
                    ->schema([
                        Toggle::make('options.ai.fal_enabled')
                            ->label('Enable FAL AI')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->helperText('Enable FAL AI for fast image generation'),

                        TextInput::make('options.ai.fal_model')
                            ->live(onBlur: true)
                            ->visible(fn(callable $get) => $get('options.ai.fal_enabled'))
                            ->label('FAL AI Model')
                            ->placeholder('fal-ai/flux/dev')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Type the model identifier (e.g. fal-ai/flux/dev, fal-ai/nano-banana). <a style="text-decoration: underline; color: #2563eb;" href="https://fal.ai/models" target="_blank">Browse available models</a>.</small>')),

                        TextInput::make('options.ai.fal_api_key')
                            ->live(onBlur: true)
                            ->password()
                            ->revealable()
                            ->visible(fn(callable $get) => $get('options.ai.fal_enabled'))
                            ->label('FAL API Key')
                            ->placeholder('Enter your FAL API key')
                            ->helperText(fn() => $this->secretHelperText('fal_api_key', '<small class="mb-2 text-muted"><a style="text-decoration: underline; color: #2563eb;" href="https://fal.ai/dashboard" target="_blank">Get your API key</a> from FAL AI dashboard.</small>')),

                        $this->testConnectionButton('fal')
                            ->visible(fn(callable $get) => $get('options.ai.fal_enabled')),
                    ]),
            ]);
    }

    public function testConnection(string $driver): void
    {
        $driverMap = [
            'openai' => \Modules\Ai\Services\Drivers\OpenAiDriver::class,
            'gemini' => \Modules\Ai\Services\Drivers\GeminiAiDriver::class,
            'openrouter' => \Modules\Ai\Services\Drivers\OpenRouterAiDriver::class,
            'ollama' => \Modules\Ai\Services\Drivers\OllamaAiDriver::class,
            'replicate' => \Modules\Ai\Services\Drivers\ReplicateAiDriver::class,
            'fal' => \Modules\Ai\Services\Drivers\FalAiDriver::class,
        ];

        $driverClass = $driverMap[$driver] ?? null;

        if (!$driverClass || !class_exists($driverClass)) {
            Notification::make()
                ->title('Driver not available')
                ->body("The driver for {$driver} is not implemented yet.")
                ->warning()
                ->send();
            return;
        }

        // Build config from current form values, falling back to saved config
        $config = $this->buildDriverConfigFromForm($driver);
        $validationErrors = $this->validateDriverConfig($driver, $config);

        if (!empty($validationErrors)) {
            Notification::make()
                ->title('Configuration incomplete')
                ->body(implode("\n", $validationErrors))
                ->warning()
                ->duration(10000)
                ->send();
            return;
        }

        try {
            $instance = new $driverClass($config);
            $response = $instance->sendToChat([
                ['role' => 'user', 'content' => 'Reply with the single word OK'],
            ], ['max_tokens' => 10]);

            $responseText = is_array($response) ? json_encode($response) : (string) $response;
            $preview = mb_substr($responseText, 0, 100);

            Notification::make()
                ->title('Connection successful')
                ->body("Response: {$preview}")
                ->success()
                ->duration(8000)
                ->send();
        } catch (\Throwable $e) {
            $errorMessage = $this->parseConnectionError($driver, $e);

            Notification::make()
                ->title('Connection failed')
                ->body($errorMessage)
                ->danger()
                ->duration(12000)
                ->send();
        }
    }

    private function validateDriverConfig(string $driver, array $config): array
    {
        $errors = [];

        // Drivers that require an API key
        $apiKeyRequired = ['openai', 'gemini', 'openrouter', 'anthropic', 'replicate', 'fal'];
        if (in_array($driver, $apiKeyRequired) && empty($config['api_key'])) {
            $labels = [
                'openai' => 'OpenAI',
                'gemini' => 'Gemini',
                'openrouter' => 'OpenRouter',
                'anthropic' => 'Anthropic',
                'replicate' => 'Replicate',
                'fal' => 'FAL AI',
            ];
            $label = $labels[$driver] ?? ucfirst($driver);
            $errors[] = "• API key is not configured. Please enter your {$label} API key.";
        }

        // Drivers that require a model
        $modelRequired = ['openai', 'gemini', 'openrouter', 'ollama'];
        if (in_array($driver, $modelRequired) && empty($config['model'])) {
            $errors[] = '• Model name is empty. Please enter a model name.';
        }

        // Ollama requires a URL
        if ($driver === 'ollama' && empty($config['url'])) {
            $errors[] = '• Ollama API URL is not configured.';
        }

        return $errors;
    }

    private function buildDriverConfigFromForm(string $driver): array
    {
        $savedConfig = config("modules.ai.drivers.{$driver}", []);
        $formData = $this->options['ai'] ?? [];

        // Map form field names to driver config keys
        $fieldMappings = [
            'openai' => [
                'openai_model' => 'model',
                'openai_api_key' => 'api_key',
                'openai_base_url' => 'base_url',
            ],
            'gemini' => [
                'gemini_model' => 'model',
                'gemini_api_key' => 'api_key',
            ],
            'openrouter' => [
                'openrouter_model' => 'model',
                'openrouter_api_key' => 'api_key',
            ],
            'ollama' => [
                'ollama_model' => 'model',
                'ollama_api_url' => 'url',
                'ollama_api_key' => 'api_key',
            ],
            'replicate' => [
                'replicate_model' => 'model',
                'replicate_api_key' => 'api_key',
            ],
            'fal' => [
                'fal_model' => 'model',
                'fal_api_key' => 'api_key',
            ],
        ];

        $mapping = $fieldMappings[$driver] ?? [];
        $config = $savedConfig;

        foreach ($mapping as $formKey => $configKey) {
            $value = $formData[$formKey] ?? null;
            if (!empty($value)) {
                $config[$configKey] = $value;
            }
        }

        return $config;
    }

    private function parseConnectionError(string $driver, \Throwable $e): string
    {
        $message = $e->getMessage();
        $code = $e->getCode();

        // Check for cURL / network-level errors
        if (stripos($message, 'could not resolve host') !== false) {
            return "DNS resolution failed — the API host could not be found. Check the URL or your network connection.";
        }
        if (stripos($message, 'connection refused') !== false) {
            if ($driver === 'ollama') {
                return "Connection refused — is Ollama running? Start it with 'ollama serve' or check the API URL.";
            }
            return "Connection refused — the server is not accepting connections. Check the URL and that the service is running.";
        }
        if (stripos($message, 'timed out') !== false || stripos($message, 'timeout') !== false) {
            return "Connection timed out — the server took too long to respond. Check your network or try again later.";
        }
        if (stripos($message, 'ssl') !== false || stripos($message, 'certificate') !== false) {
            return "SSL/TLS error — there is a problem with the server's certificate. " . mb_substr($message, 0, 150);
        }

        // Check for HTTP status codes in the message
        if (preg_match('/\b401\b/', $message) || stripos($message, 'unauthorized') !== false || stripos($message, 'invalid.*api.?key') !== false || stripos($message, 'incorrect api key') !== false) {
            return "Authentication failed (401) — your API key is invalid or expired. Please check and re-enter it.";
        }
        if (preg_match('/\b403\b/', $message) || stripos($message, 'forbidden') !== false) {
            return "Access forbidden (403) — your API key doesn't have permission to access this resource. Check your account permissions.";
        }
        if (preg_match('/\b404\b/', $message) || stripos($message, 'not found') !== false) {
            if (stripos($message, 'model') !== false) {
                return "Model not found (404) — the model name is invalid or not available. Check the model identifier.";
            }
            return "Endpoint not found (404) — check the API URL. The service may have changed its endpoint.";
        }
        if (preg_match('/\b429\b/', $message) || stripos($message, 'rate limit') !== false || stripos($message, 'too many requests') !== false) {
            return "Rate limited (429) — too many requests. Wait a moment and try again, or check your plan's usage limits.";
        }
        if (preg_match('/\b5\d{2}\b/', $message) || stripos($message, 'internal server error') !== false || stripos($message, 'server error') !== false) {
            return "Server error — the provider is experiencing issues. Try again later. Details: " . mb_substr($message, 0, 120);
        }
        if (stripos($message, 'insufficient') !== false && (stripos($message, 'quota') !== false || stripos($message, 'balance') !== false || stripos($message, 'funds') !== false)) {
            return "Insufficient quota or balance — your account may need billing setup or has exceeded its limit.";
        }

        // Fallback: show the raw error, truncated
        return mb_substr($message, 0, 300);
    }

    private function testConnectionButton(string $driver): Placeholder
    {
        $label = match ($driver) {
            'openai' => 'OpenAI',
            'gemini' => 'Gemini',
            'openrouter' => 'OpenRouter',
            'ollama' => 'Ollama',
            'anthropic' => 'Anthropic',
            'replicate' => 'Replicate',
            'fal' => 'FAL AI',
            default => ucfirst($driver),
        };

        return Placeholder::make("test_connection_{$driver}")
            ->hiddenLabel()
            ->content(fn () => new HtmlString(
                '<button type="button" wire:click="testConnection(\'' . $driver . '\')" wire:loading.attr="disabled" '
                . 'style="background-color: #2563eb; color: #fff; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.375rem; border: none; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: background-color 0.15s;" '
                . 'onmouseover="this.style.backgroundColor=\'#1d4ed8\'" onmouseout="this.style.backgroundColor=\'#2563eb\'">'
                . '<svg wire:loading.remove wire:target="testConnection(\'' . $driver . '\')" style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>'
                . '<span wire:loading.remove wire:target="testConnection(\'' . $driver . '\')">Test Connection</span>'
                . '<svg wire:loading wire:target="testConnection(\'' . $driver . '\')" style="width:1rem;height:1rem;" class="animate-spin" fill="none" viewBox="0 0 24 24"><circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>'
                . '<span wire:loading wire:target="testConnection(\'' . $driver . '\')">Testing...</span>'
                . '</button>'
            ));
    }

    private function extractSecretOptionKey(string $propertyName): ?string
    {
        if (!str_starts_with($propertyName, 'options.ai.')) {
            return null;
        }

        $optionKey = substr($propertyName, strlen('options.ai.'));

        return in_array($optionKey, $this->secretOptionKeys, true) ? $optionKey : null;
    }

    private function storeSecretOption(string $optionKey, mixed $value): void
    {
        /** @var PassSecretStore $secretStore */
        $secretStore = app(PassSecretStore::class);

        if (! $secretStore->isEnabled()) {
            $this->options['ai'][$optionKey] = '';

            \Filament\Notifications\Notification::make()
                ->title('Secret store is not enabled')
                ->body('Configure the pass secret store before saving AI provider API keys.')
                ->danger()
                ->send();

            return;
        }

        $existingStoredValue = get_option($optionKey, 'ai');
        $secret = trim((string) $value);

        if ($secret === '') {
            if ($secretStore->isReference($existingStoredValue)) {
                $secretStore->delete($existingStoredValue);
            }

            $this->saveAiOptionValue($optionKey, '');
            $this->forgetOptionCache();

            \Filament\Notifications\Notification::make()
                ->title('Secret removed')
                ->success()
                ->send();

            return;
        }

        $reference = $secretStore->storeAiProviderSecret($optionKey, $secret);
        $this->saveAiOptionValue($optionKey, $reference);
        $this->forgetOptionCache();
        $this->options['ai'][$optionKey] = '';

        \Filament\Notifications\Notification::make()
            ->title('Secret stored securely')
            ->success()
            ->send();
    }

    private function saveAiOptionValue(string $optionKey, string $value): void
    {
        $optionToSave = [
            'option_key' => $optionKey,
            'option_value' => $value,
            'option_group' => 'ai',
        ];

        if ($this->moduleNameForOption) {
            $optionToSave['module'] = $this->moduleNameForOption;
        }

        save_option($optionToSave);
    }

    private function forgetOptionCache(): void
    {
        Cache::forget('admin_settings_options_' . implode('_', $this->getOptionGroups()));
    }

    private function secretHelperText(string $optionKey, string $defaultHtml): HtmlString
    {
        /** @var PassSecretStore $secretStore */
        $secretStore = app(PassSecretStore::class);
        $storedValue = get_option($optionKey, 'ai');

        if ($secretStore->isReference($storedValue)) {
            return new HtmlString('<small class="mb-2 text-muted">Stored securely in <code>pass</code>. Enter a new value to rotate the secret.</small>');
        }

        if (filled($storedValue)) {
            return new HtmlString('<small class="mb-2 text-warning-600">Legacy plaintext secret detected. Enter a new value to migrate it into <code>pass</code>.</small>');
        }

        if (! $secretStore->isEnabled()) {
            return new HtmlString('<small class="mb-2 text-danger-600">The <code>pass</code> secret store is disabled. Configure it before saving provider API keys.</small>');
        }

        return new HtmlString($defaultHtml);
    }
}
