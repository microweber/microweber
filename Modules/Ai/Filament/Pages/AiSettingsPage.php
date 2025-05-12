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

                Section::make('AI settings')
                    ->view('filament-forms::sections.section')
                    ->description('Configure your AI settings')
                    ->schema([

                        Select::make('options.ai.default_driver')
                            ->label('Set AI model')
                            ->live()
                            ->options([
                                'openai' => 'OpenAI',
                                'openrouter' => 'OpenRouter',
                                'ollama' => 'Ollama',
                            ])
                        ,


                        Select::make('options.ai.openai_model')
                            ->label('Set AI model')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.default_driver') === 'openai')
                            ->options([
                                'gpt-3.5-turbo' => 'GPT 3.5 Turbo',
                                'gpt-4' => 'GPT 4',

                            ])
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">
                                    <a href="https://platform.openai.com/docs/models/gpt-4" target="_blank">Learn more</a> about the models.
                                    </small>');
                            }),


                        TextInput::make('options.ai.openai_api_key')
                            ->label('OpenAI API Key')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.default_driver') === 'openai')
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">
                                    <a href="https://platform.openai.com/signup" target="_blank">Sign up</a> for an OpenAI account to get your API key.
                                    </small>');
                            })
                            ->placeholder('Enter your OpenAI API key'),


                        Select::make('options.ai.openrouter_model')
                            ->label('Set OpenRouter AI model')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.default_driver') === 'openrouter')
                            ->options([

                                'meta-llama/llama-3.3-70b-instruct' => 'Meta Llama 3.3 70B Instruct',
                                'meta-llama/llama-3-8b-instruct' => 'Meta Llama 3 8B Instruct',


                            ])->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">
                                    <a href="https://openrouter.ai/" target="_blank">Learn more</a> about the models.
                                    </small>');
                            }),


                        TextInput::make('options.ai.openrouter_api_key')
                            ->label('Open Router API Key')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.default_driver') === 'openrouter')
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">
                                        <a href="https://openrouter.ai/signup" target="_blank">Sign up</a> for an OpenRouter account to get your API key.
                                        </small>');

                            })->placeholder('Enter your OpenRouter API key'),


                        Select::make('options.ai.ollama_model')
                            ->label('Set Ollama AI model')
                            ->live()
                            ->visible(fn(callable $get) => $get('options.ai.default_driver') === 'ollama')
                            ->options([
                                'llama3.2' => 'Llama 3.2',
                            ])->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">
                                        <a href="https://ollama.com/" target="_blank">Learn more</a> about the models.
                                        </small>');
                            }),


                    ]),
            ]);
    }

}
