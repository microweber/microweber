<?php

namespace Modules\Comments\Filament\Pages;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use Modules\Settings\Filament\Pages\Settings;

class CommentsModuleSettingsAdmin extends AdminSettingsPage
{

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Comments Settings';
    protected static ?string $navigationLabel = 'Comments';
    protected static ?int $navigationSort = 10;
    public array $optionGroups = [
        'website'
    ];
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Settings')
                    ->schema([
                        Toggle::make('options.comments.enable_comments')
                            ->label('Enable Comments')
                            ->helperText('Enable or disable comments globally')
                            ->default(true),

                        Toggle::make('options.comments.require_moderation')
                            ->label('Require Moderation')
                            ->helperText('New comments must be approved before being published')
                            ->default(true),

                        Toggle::make('options.comments.allow_guest_comments')
                            ->label('Allow Guest Comments')
                            ->helperText('Allow comments from non-registered users')
                            ->default(true),

                        Toggle::make('options.comments.allow_replies')
                            ->label('Allow Replies')
                            ->helperText('Allow users to reply to comments')
                            ->default(true),

                        TextInput::make('options.comments.max_comment_length')
                            ->label('Maximum Comment Length')
                            ->type('number')
                            ->default(1000)
                            ->minValue(1)
                            ->maxValue(10000),

                        TextInput::make('options.comments.min_comment_length')
                            ->label('Minimum Comment Length')
                            ->type('number')
                            ->default(2)
                            ->minValue(1)
                            ->maxValue(100),
                    ]),

                Section::make('Display Settings')
                    ->schema([
                        Select::make('options.comments.default_sort')
                            ->label('Default Sort Order')
                            ->options([
                                'newest' => 'Newest First',
                                'oldest' => 'Oldest First',
                                'most_liked' => 'Most Liked'
                            ])
                            ->default('newest'),

                        TextInput::make('options.comments.comments_per_page')
                            ->label('Comments Per Page')
                            ->type('number')
                            ->default(10)
                            ->minValue(1)
                            ->maxValue(100),

                        Toggle::make('options.comments.show_user_avatar')
                            ->label('Show User Avatar')
                            ->helperText('Display user avatars next to comments')
                            ->default(true),

                        Toggle::make('options.comments.show_user_website')
                            ->label('Show User Website')
                            ->helperText('Display user website link if provided')
                            ->default(true),
                    ]),

                Section::make('Email Notifications')
                    ->schema([
                        Toggle::make('options.comments.notify_admin')
                            ->label('Notify Admin')
                            ->helperText('Send email notification to admin for new comments')
                            ->default(true),

                        TextInput::make('options.comments.admin_email')
                            ->label('Admin Email')
                            ->email()
                            ->helperText('Email address for comment notifications'),

                        Toggle::make('options.comments.notify_users')
                            ->label('Notify Users')
                            ->helperText('Send email notification to users when someone replies to their comment')
                            ->default(true),
                    ]),

                Section::make('Anti-Spam Settings')
                    ->schema([
                        Toggle::make('options.comments.enable_captcha')
                            ->label('Enable Captcha')
                            ->helperText('Require captcha verification for guest comments')
                            ->default(true),

                        Toggle::make('options.comments.block_spam_keywords')
                            ->label('Block Spam Keywords')
                            ->helperText('Automatically mark comments with spam keywords as spam')
                            ->default(true),

                        Textarea::make('options.comments.spam_keywords')
                            ->label('Spam Keywords')
                            ->helperText('Enter keywords separated by commas')
                            ->rows(3),

                        TextInput::make('options.comments.max_links')
                            ->label('Maximum Links')
                            ->helperText('Maximum number of links allowed in a comment (0 for unlimited)')
                            ->type('number')
                            ->default(2)
                            ->minValue(0)
                            ->maxValue(100),
                    ]),
            ]);
    }
}
