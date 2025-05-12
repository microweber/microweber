<?php

namespace Modules\Ai\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AiSettingsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static bool $shouldRegisterNavigation = true;

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Ai Settings';

    protected static string $description = 'Configure your ai settings';

    protected static ?string $navigationGroup = 'Website Settings';
    protected static ?int $navigationSort = 3000;

    public array $optionGroups = [
        'ai'
    ];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General AI Settings')
                    ->view('filament-forms::sections.section')
                    ->schema([
                        Select::make('options.ai.default_driver')
                            ->label('Set default AI provider')
                            ->live()
                            ->options([
                                'openai' => 'OpenAI',
                                'openrouter' => 'OpenRouter',
                             //   'ollama' => 'Ollama',
                              //  'gemini' => 'Google Gemini',
                            ])
                    ]),

                Section::make('OpenAI Settings')
                    ->view('filament-forms::sections.section')
                    ->visible(fn(callable $get) => $get('options.ai.default_driver') === 'openai')
                    ->schema([
                        Select::make('options.ai.openai_model')
                            ->label('OpenAI Model')
                            ->options(config('modules.ai.drivers.openai.models', [
                                'gpt-3.5-turbo' => 'GPT 3.5 Turbo',
                                'gpt-4' => 'GPT 4',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://platform.openai.com/docs/models/gpt-4" target="_blank">Learn more</a> about the models.</small>')),

                        TextInput::make('options.ai.openai_api_key')
                            ->label('OpenAI API Key')
                            ->placeholder('Enter your OpenAI API key')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://platform.openai.com/signup" target="_blank">Sign up</a> for an OpenAI account to get your API key.</small>')),
                    ]),

                Section::make('Google Gemini Settings')
                    ->view('filament-forms::sections.section')
                    ->visible(fn(callable $get) => $get('options.ai.default_driver') === 'gemini')
                    ->schema([
                        Select::make('options.ai.gemini_model')
                            ->label('Gemini Model')
                            ->options(config('modules.ai.drivers.gemini.models', [
                                'gemini-pro' => 'Gemini Pro',
                                'gemini-pro-vision' => 'Gemini Pro Vision',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://ai.google.dev/models/gemini" target="_blank">Learn more</a> about the models.</small>')),

                        TextInput::make('options.ai.gemini_api_key')
                            ->label('Gemini API Key')
                            ->placeholder('Enter your Gemini API key')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://makersuite.google.com/app/apikey" target="_blank">Get your API key</a> from Google AI Studio.</small>')),
                    ]),

                Section::make('OpenRouter Settings')
                    ->view('filament-forms::sections.section')
                    ->visible(fn(callable $get) => $get('options.ai.default_driver') === 'openrouter')
                    ->schema([
                        Select::make('options.ai.openrouter_model')
                            ->label('OpenRouter Model')
                            ->options(config('modules.ai.drivers.openrouter.models', [
                                'meta-llama/llama-3.3-70b-instruct' => 'Meta Llama 3.3 70B Instruct',
                                'meta-llama/llama-3-8b-instruct' => 'Meta Llama 3 8B Instruct',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://openrouter.ai/" target="_blank">Learn more</a> about the models.</small>')),

                        TextInput::make('options.ai.openrouter_api_key')
                            ->label('OpenRouter API Key')
                            ->placeholder('Enter your OpenRouter API key')
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://openrouter.ai/signup" target="_blank">Sign up</a> for an OpenRouter account.</small>')),
                    ]),

                Section::make('Ollama Settings')
                    ->view('filament-forms::sections.section')
                    ->visible(fn(callable $get) => $get('options.ai.default_driver') === 'ollama')
                    ->schema([
                        Select::make('options.ai.ollama_model')
                            ->label('Ollama Model')
                            ->options(config('modules.ai.drivers.ollama.models', [
                                'llama3.2' => 'Llama 3.2',
                            ]))
                            ->helperText(fn() => new HtmlString('<small class="mb-2 text-muted"><a href="https://ollama.com/" target="_blank">Learn more</a> about the models.</small>')),
                    ]),
            ]);
    }
}
