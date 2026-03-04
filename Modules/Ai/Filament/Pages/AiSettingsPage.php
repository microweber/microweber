<?php

namespace Modules\Ai\Filament\Pages;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

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
return auth()->user()->can('manage_ai_settings');
}

public function updated($propertyName, $value): void
{
parent::updated($propertyName, $value);

// Clear config cache when AI settings are updated
if (str_starts_with($propertyName, 'options.ai.')) {
Artisan::call('config:clear');
}
}



    public function form(Schema $schema): Schema
    {

        $isDisabled = config('modules.ai.disable_settings', false);

        if($isDisabled){
            return $schema
                ->schema([
                    Section::make('AI settings are disabled')
                        ->view('filament-forms::sections.section')
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
->view('filament-forms::sections.section')
->schema([
Toggle::make('options.ai.enabled')
->label('Enable AI Functionality')
->live()
->onIcon('heroicon-m-check')
->offIcon('heroicon-m-x-mark')
->helperText('Enable or disable all AI features globally'),

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
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('filament-forms::sections.section')
                    ->schema([
                        Toggle::make('options.ai.openai_enabled')
                            ->label('Enable OpenAI')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')

                        ,

                        Select::make('options.ai.openai_model')
                            ->live()
                            ->label('OpenAI Model')
                            ->visible(fn(callable $get) => $get('options.ai.openai_enabled'))
                            ->options(config('modules.ai.drivers.openai.models', [
                                'gpt-3.5-turbo' => 'GPT 3.5 Turbo',
                                'gpt-4' => 'GPT 4',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://platform.openai.com/docs/models/gpt-4" target="_blank">Learn more</a> about the models.</small>')),

                        TextInput::make('options.ai.openai_api_key')
                            ->live()
                            ->label('OpenAI API Key')
                            ->visible(fn(callable $get) => $get('options.ai.openai_enabled'))
                            ->placeholder('Enter your OpenAI API key')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://platform.openai.com/signup" target="_blank">Sign up</a> for an OpenAI account to get your API key.</small>')),
                    ]),

                Section::make('Google Gemini Settings')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('filament-forms::sections.section')
                    ->schema([
                        Toggle::make('options.ai.gemini_enabled')
                            ->label('Enable Google Gemini')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')

                        ,

                        Select::make('options.ai.gemini_model')
                            ->label('Gemini Model')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.gemini_enabled'))
                            ->options(config('modules.ai.drivers.gemini.models', [
                                'gemini-pro' => 'Gemini Pro',
                                'gemini-pro-vision' => 'Gemini Pro Vision',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://ai.google.dev/models/gemini" target="_blank">Learn more</a> about the models.</small>')),

                        TextInput::make('options.ai.gemini_api_key')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.gemini_enabled'))
                            ->label('Gemini API Key')
                            ->placeholder('Enter your Gemini API key')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://makersuite.google.com/app/apikey" target="_blank">Get your API key</a> from Google AI Studio.</small>')),
                    ]),

                Section::make('OpenRouter Settings')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('filament-forms::sections.section')
                    ->schema([
                        Toggle::make('options.ai.openrouter_enabled')
                            ->label('Enable OpenRouter')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')

                        ,

                        Select::make('options.ai.openrouter_model')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.openrouter_enabled'))
                            ->label('OpenRouter Model')
                            ->options(config('modules.ai.drivers.openrouter.models', [
                                'meta-llama/llama-3.3-70b-instruct' => 'Meta Llama 3.3 70B Instruct',
                                'meta-llama/llama-3-8b-instruct' => 'Meta Llama 3 8B Instruct',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://openrouter.ai/" target="_blank">Learn more</a> about the models.</small>')),

                        TextInput::make('options.ai.openrouter_api_key')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.openrouter_enabled'))
                            ->label('OpenRouter API Key')
                            ->placeholder('Enter your OpenRouter API key')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://openrouter.ai/signup" target="_blank">Sign up</a> for an OpenRouter account.</small>')),
                    ]),

                Section::make('Ollama Settings')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('filament-forms::sections.section')
                    ->schema([
                        Toggle::make('options.ai.ollama_enabled')
                            ->label('Enable Ollama')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')

                        ,

                        Select::make('options.ai.ollama_model')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.ollama_enabled'))
                            ->label('Ollama Model')
                            ->options(config('modules.ai.drivers.ollama.models', [
                                'llama3.2' => 'Llama 3.2',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://ollama.com/" target="_blank">Learn more</a> about the models.</small>')),

                        TextInput::make('options.ai.ollama_api_url')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.ollama_enabled'))
                            ->label('Ollama API URL')
                            ->placeholder('http://localhost:11434/api/generate')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Enter the URL for your local or remote Ollama instance.</small>')),
                    ]),

                Section::make('Anthropic/Claude Settings')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('filament-forms::sections.section')
                    ->schema([
                        Toggle::make('options.ai.anthropic_enabled')
                            ->label('Enable Anthropic/Claude')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark'),
                        Select::make('options.ai.anthropic_model')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.anthropic_enabled'))
                            ->label('Anthropic/Claude Model')
                            ->options(config('modules.ai.drivers.anthropic.models', [

                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://www.anthropic.com/claude" target="_blank">Learn more</a> about the models.</small>')),
                        TextInput::make('options.ai.anthropic_api_key')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.anthropic_enabled'))
                            ->label('Anthropic API Key')
                            ->placeholder('Enter your Anthropic API key')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted">Find your API key in your Anthropic account dashboard.</small>')),
                    ]),

                Section::make('Replicate Settings')
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('filament-forms::sections.section')
                    ->schema([
                        Toggle::make('options.ai.replicate_enabled')
                            ->label('Enable Replicate')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark'),
                        Select::make('options.ai.replicate_model')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.replicate_enabled'))
                            ->label('Image Generation Model')
                            ->options(config('modules.ai.drivers.replicate.models', [
                                'stabilityai/stable-diffusion-xl-base-1.0' => 'Stable Diffusion XL',
                                'stabilityai/stable-diffusion-xl-1024-v1-0' => 'Stable Diffusion XL 1024',
                                'stabilityai/stable-diffusion-xl-1024-v1-0-inpainting' => 'Stable Diffusion XL Inpainting',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://replicate.com/collections/text-to-image" target="_blank">Learn more</a> about available models.</small>')),

                        TextInput::make('options.ai.replicate_api_key')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.replicate_enabled'))
                            ->label('Replicate API Token')
                            ->placeholder('Enter your Replicate API token')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://replicate.com/account/api-tokens" target="_blank">Get your API token</a> from Replicate.</small>')),

                    ]),

Section::make('Supadata Settings')
->visible(fn(callable $get) => $get('options.ai.enabled'))
->view('filament-forms::sections.section')
->schema([
Toggle::make('options.ai.supadata_enabled')
->label('Enable Supadata')
->live()
->onIcon('heroicon-m-check')
->offIcon('heroicon-m-x-mark')
->helperText('Enable Supadata AI service for text generation'),

Select::make('options.ai.supadata_model')
->live()
->visible(fn(callable $get) => $get('options.ai.supadata_enabled'))
->label('Supadata Model')
->options(config('modules.ai.drivers.supadata.models', [
'supadata-default' => 'Supadata Default',
'supadata-pro' => 'Supadata Pro',
'supadata-turbo' => 'Supadata Turbo',
]))
->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://supadata.com/" target="_blank">Learn more</a> about available models.</small>')),

TextInput::make('options.ai.supadata_api_key')
->live()
->visible(fn(callable $get) => $get('options.ai.supadata_enabled'))
->label('Supadata API Key')
->placeholder('Enter your Supadata API key')
->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://supadata.com/api-keys" target="_blank">Get your API key</a> from Supadata dashboard.</small>')),

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
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('filament-forms::sections.section')
                    ->schema([
                        Toggle::make('options.ai.tavily_enabled')
                            ->label('Enable TAVILY Search')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->helperText('Enable TAVILY for AI-powered web search capabilities'),

                        TextInput::make('options.ai.tavily_api_key')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.tavily_enabled'))
                            ->label('TAVILY API Key')
                            ->placeholder('Enter your TAVILY API key')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://tavily.com/" target="_blank">Sign up</a> for a TAVILY account to get your API key.</small>')),

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
                    ->visible(fn(callable $get) => $get('options.ai.enabled'))
                    ->view('filament-forms::sections.section')
                    ->schema([
                        Toggle::make('options.ai.fal_enabled')
                            ->label('Enable FAL AI')
                            ->live()
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->helperText('Enable FAL AI for fast image generation'),

                        Select::make('options.ai.fal_model')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.fal_enabled'))
                            ->label('FAL AI Model')
                            ->options(config('modules.ai.drivers.fal.models', [
                                'fal-ai/nano-banana' => 'Nano Banana',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://fal.ai/explore" target="_blank">Learn more</a> about available models.</small>')),

                        TextInput::make('options.ai.fal_api_key')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.fal_enabled'))
                            ->label('FAL API Key')
                            ->placeholder('Enter your FAL API key')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://fal.ai/dashboard" target="_blank">Get your API key</a> from FAL AI dashboard.</small>')),
                    ]),
            ]);
    }
}
