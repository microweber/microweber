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
        'comments'
    ];
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Settings')
                    ->schema([
                        Toggle::make('options.comments.enable_comments')
                            ->live()
                            ->label('Enable Comments')
                            ->helperText('Enable or disable comments globally'),

                        Toggle::make('options.comments.require_moderation')
                            ->live()
                            ->label('Require Moderation')
                            ->helperText('New comments must be approved before being published'),

                        Toggle::make('options.comments.allow_guest_comments')
                            ->live()
                            ->label('Allow Guest Comments')
                            ->helperText('Allow comments from non-registered users'),

                        Toggle::make('options.comments.allow_replies')
                            ->live()
                            ->label('Allow Replies')
                            ->helperText('Allow users to reply to comments'),

                        TextInput::make('options.comments.max_comment_length')
                            ->live()
                            ->label('Maximum Comment Length')
                            ->type('number')
                            ->minValue(1)
                            ->maxValue(10000),

                        TextInput::make('options.comments.min_comment_length')
                            ->live()
                            ->label('Minimum Comment Length')
                            ->type('number')
                            ->minValue(1)
                            ->maxValue(100),
                    ]),

                Section::make('Display Settings')
                    ->schema([
                        Select::make('options.comments.default_sort')
                            ->live()
                            ->label('Default Sort Order')
                            ->options([
                                'newest' => 'Newest First',
                                'oldest' => 'Oldest First',
                                'most_liked' => 'Most Liked'
                            ]),

                        TextInput::make('options.comments.comments_per_page')
                            ->live()
                            ->label('Comments Per Page')
                            ->type('number')
                            ->minValue(1)
                            ->maxValue(100),

                        Toggle::make('options.comments.show_user_avatar')
                            ->live()
                            ->label('Show User Avatar')
                            ->helperText('Display user avatars next to comments'),

                        Toggle::make('options.comments.show_user_website')
                            ->live()
                            ->label('Show User Website')
                            ->helperText('Display user website link if provided'),
                    ]),

                Section::make('Email Notifications')
                    ->schema([
                        Toggle::make('options.comments.notify_admin')
                            ->live()
                            ->label('Notify Admin')
                            ->helperText('Send email notification to admin for new comments'),

                        TextInput::make('options.comments.admin_email')
                            ->live()
                            ->label('Admin Email')
                            ->email()
                            ->helperText('Email address for comment notifications'),

                        Toggle::make('options.comments.notify_users')
                            ->live()
                            ->label('Notify Users')
                            ->helperText('Send email notification to users when someone replies to their comment'),
                    ]),

                Section::make('Anti-Spam Settings')
                    ->schema([
                        Toggle::make('options.comments.enable_captcha')
                            ->live()
                            ->label('Enable Captcha')
                            ->helperText('Require captcha verification for guest comments'),

                        Toggle::make('options.comments.block_spam_keywords')
                            ->live()
                            ->label('Block Spam Keywords')
                            ->helperText('Automatically mark comments with spam keywords as spam'),

                        Textarea::make('options.comments.spam_keywords')
                            ->live()
                            ->label('Spam Keywords')
                            ->helperText('Enter keywords separated by commas')
                            ->rows(3),

                        TextInput::make('options.comments.max_links')
                            ->live()
                            ->label('Maximum Links')
                            ->helperText('Maximum number of links allowed in a comment (0 for unlimited)')
                            ->type('number')
                            ->minValue(0)
                            ->maxValue(100),
                    ]),
            ]);
    }
}
