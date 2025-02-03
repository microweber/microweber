<?php

namespace Modules\Backup\Filament\Pages;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Pages\Page;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;

class CreateBackup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'modules.backup::filament.pages.create-backup';

    public ?string $backup_type = 'content_backup';
    public array $include_tables = [];
    public array $include_modules = [];
    public array $include_templates = [];
    public bool $include_media = false;
    public ?string $backup_filename = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Backup Type')
                        ->description('Choose the type of backup you want to create')
                        ->schema([
                            RadioDeck::make('backup_type')
                                ->label('Backup Type')
                                ->descriptions([
                                    'content_backup' => 'Create backup of your sites without sensitive information. This will create a zip with live-edit css, media, post categories &amp; pages.',
                                    'custom_backup' => 'Create backup with custom selected tables, users, api_keys, media, modules, templates...',
                                    'full_backup' => 'Create full backup of your sites with all data. This will create a zip with all data from your database. Include sensitive information like users, passwords, api keys, settings.',
                                ])

                                ->options([
                                    'content_backup' => 'Content Backup',
                                    'custom_backup' => 'Custom Backup',
                                    'full_backup' => 'Full Backup',
                                ])
                                ->default('content_backup')
                                ->required()
                        ]),

                    Wizard\Step::make('Custom Options')
                        ->description('Select what to include in your backup')
                        ->schema([
                            Section::make('Database Tables')
                                ->schema([
                                    Toggle::make('include_tables')
                                        ->label('Include Tables')
                                        ->onIcon('heroicon-m-check')
                                        ->offIcon('heroicon-m-x-mark'),
                                ])
                                ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),

                            Section::make('Media Files')
                                ->schema([
                                    Toggle::make('include_media')
                                        ->label('Include Media Files')
                                        ->onIcon('heroicon-m-check')
                                        ->offIcon('heroicon-m-x-mark'),
                                ])
                                ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),

                            Section::make('Modules')
                                ->schema([
                                    Toggle::make('include_modules')
                                        ->label('Include Modules')
                                        ->onIcon('heroicon-m-check')
                                        ->offIcon('heroicon-m-x-mark'),
                                ])
                                ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),

                            Section::make('Templates')
                                ->schema([
                                    Toggle::make('include_templates')
                                        ->label('Include Templates')
                                        ->onIcon('heroicon-m-check')
                                        ->offIcon('heroicon-m-x-mark'),
                                ])
                                ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),
                        ])
                        ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),

                    Wizard\Step::make('Generate Backup')
                        ->description('Configure and generate your backup')
                        ->schema([
                            TextInput::make('backup_filename')
                                ->label('Backup Filename')
                                ->placeholder('Enter backup filename (optional)')
                                ->helperText('Leave empty for auto-generated filename'),
                        ]),
                ])->submitAction('Generate Backup')
            ]);
    }

    public function submit(): void
    {
        // Handle the backup generation here
        // You can access the form data through the public properties
    }
}
