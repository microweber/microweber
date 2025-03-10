<?php

namespace Modules\Updater\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Updater\Filament\Resources\UpdaterResource\Pages\ManageUpdater;

class UpdaterResource extends Resource
{
    protected static ?string $model = null;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Updater';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 100;
    protected static ?string $slug = 'updater';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Update Settings')
                    ->schema([
                        Forms\Components\Select::make('max_receive_speed_download')
                            ->label('Max receive speed download (per second)')
                            ->helperText('Set the maximum download speed for updates')
                            ->options([
                                '0' => 'Unlimited',
                                '5' => '5MB/s',
                                '2' => '2MB/s',
                                '1' => '1MB/s',
                            ])
                            ->default('0'),

                        Forms\Components\Select::make('download_method')
                            ->label('Download method')
                            ->helperText('Select the method used to download updates')
                            ->options([
                                'curl' => 'CURL',
                                'file_get_contents' => 'File Get Contents',
                            ])
                            ->default('curl'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('version')
                    ->label('Current Version')
                    ->getStateUsing(fn () => MW_VERSION),

                Tables\Columns\TextColumn::make('latest_version')
                    ->label('Latest Version')
                    ->getStateUsing(fn () => self::getLatestVersion()),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function () {
                        $currentVersion = MW_VERSION;
                        $latestVersion = self::getLatestVersion();

                        if (\Composer\Semver\Comparator::equalTo($latestVersion, $currentVersion)) {
                            return 'up-to-date';
                        } elseif (\Composer\Semver\Comparator::greaterThan($latestVersion, $currentVersion)) {
                            return 'update-available';
                        }

                        return 'unknown';
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'up-to-date' => 'heroicon-o-check-circle',
                        'update-available' => 'heroicon-o-exclamation-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'up-to-date' => 'success',
                        'update-available' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('update')
                    ->label('Update Now')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->url(fn () => route('api.updater.update-now'))
                    ->visible(function () {
                        $currentVersion = MW_VERSION;
                        $latestVersion = self::getLatestVersion();

                        return \Composer\Semver\Comparator::greaterThan($latestVersion, $currentVersion) && self::canUpdate();
                    }),

                Tables\Actions\Action::make('reinstall')
                    ->label('Reinstall')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->url(fn () => route('api.updater.update-now'))
                    ->visible(function () {
                        $currentVersion = MW_VERSION;
                        $latestVersion = self::getLatestVersion();

                        return \Composer\Semver\Comparator::equalTo($latestVersion, $currentVersion) && self::canUpdate();
                    }),

                Tables\Actions\Action::make('copy_standalone')
                    ->label('Copy Standalone Updater')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('success')
                    ->action(function () {
                        \Modules\Updater\Support\CopyStandaloneUpdater::copy();

                        \Filament\Notifications\Notification::make()
                            ->title('Standalone updater copied')
                            ->body('The standalone updater has been copied to the public directory.')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateActions([
                // No actions for empty state
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUpdater::route('/'),
        ];
    }

    /**
     * Get the latest version from the update server
     */
    private static function getLatestVersion()
    {
        return cache()->remember('standalone_updater_latest_version', 1440, function () {
            $updateApi = 'http://updater.microweberapi.com/builds/master/version.txt';
            $version = app()->url_manager->download($updateApi);
            if ($version) {
                $version = trim($version);
                return $version;
            }
            return MW_VERSION;
        });
    }

    /**
     * Check if the system can be updated
     */
    private static function canUpdate(): bool
    {
        $canUpdate = true;
        $messages = self::getCanUpdateMessages();

        if (!empty($messages)) {
            $canUpdate = false;
        }

        return $canUpdate;
    }

    /**
     * Get messages about why the system can't be updated
     */
    private static function getCanUpdateMessages(): array
    {
        $messages = [];
        $projectMainDir = dirname(dirname(dirname(dirname(__DIR__))));

        if (is_dir($projectMainDir . DS . '.git')) {
            $messages[] = 'The git repository is recognized on your server.';
        }

        if (!class_exists('ZipArchive')) {
            $messages[] = 'ZipArchive PHP extension is required auto updater.';
        }

        if (!function_exists('curl_init')) {
            $messages[] = 'The Curl PHP extension is required auto updater.';
        }

        if (!function_exists('json_decode')) {
            $messages[] = 'The JSON PHP extension is required auto updater.';
        }

        if (!is_writable($projectMainDir . DS . 'src')) {
            $messages[] = 'The src folder must be writable.';
        }

        if (!is_writable($projectMainDir . DS . 'userfiles')) {
            $messages[] = 'The userfiles folder must be writable.';
        }

        if (!is_writable($projectMainDir . DS . 'storage')) {
            $messages[] = 'The storage folder must be writable.';
        }

        if (is_link($projectMainDir . DS . 'vendor')) {
            $messages[] = 'The vendor folder must not be a symlink.';
        }

        if (!is_writable($projectMainDir . DS . 'vendor')) {
            $messages[] = 'The vendor folder must be writable.';
        }

        if (function_exists('disk_free_space')) {
            $bytes = disk_free_space($projectMainDir);
            $si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
            $base = 1024;
            $class = min((int)log($bytes, $base), count($si_prefix) - 1);

            if (($bytes / pow($base, $class)) < 1) {
                $messages[] = 'The minimum required free disk space is 1GB, you have ' . sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class] . ' on your server.';
            }
        }

        return $messages;
    }
}
