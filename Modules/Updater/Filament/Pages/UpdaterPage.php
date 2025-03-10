<?php

namespace Modules\Updater\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Modules\Updater\Services\UpdaterHelper;

class UpdaterPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Updater';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 100;
    protected static ?string $slug = 'updater';
    protected static string $view = 'modules.updater::filament.pages.updater';

    public $currentVersion;
    public $latestVersion;
    public $updateAvailable = false;
    public $canUpdate = true;
    public $updateMessages = [];
    public $selectedBranch = 'master';

    public function mount(): void
    {
        $this->currentVersion = MW_VERSION;
        $this->selectedBranch = config('modules.updater.branch') ?? 'master'; // Default to first branch or master
        $updaterHelper = app(UpdaterHelper::class);
        $this->latestVersion = $updaterHelper->getLatestVersion($this->selectedBranch);

        if (\Composer\Semver\Comparator::greaterThan($this->latestVersion, $this->currentVersion)) {
            $this->updateAvailable = true;
        }

        $this->updateMessages = $updaterHelper->getCanUpdateMessages();
        if (!empty($this->updateMessages)) {
            $this->canUpdate = false;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('select_branch')
                ->label('Select Branch')
                ->icon('heroicon-o-code-bracket')
                ->form([
                    Select::make('branch')
                        ->label('Branch')
                        ->options(config('modules.updater.branches'))
                        ->default($this->selectedBranch)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->selectedBranch = $data['branch'];

                    // Clear the cache to force a fresh check
                    cache()->forget('standalone_updater_latest_version');
                    cache()->forget('standalone_updater_latest_version_composer_json');

                    // Refresh the data with the new branch
                    $updaterHelper = app(UpdaterHelper::class);
                    $this->latestVersion = $updaterHelper->getLatestVersion($this->selectedBranch);

                    if (\Composer\Semver\Comparator::greaterThan($this->latestVersion, $this->currentVersion)) {
                        $this->updateAvailable = true;
                        Notification::make()
                            ->title('Update available')
                            ->body("A new version ({$this->latestVersion}) is available for download from the {$this->selectedBranch} branch.")
                            ->success()
                            ->send();
                    } else {
                        $this->updateAvailable = false;
                        Notification::make()
                            ->title('No updates available')
                            ->body("You are running the latest version from the {$this->selectedBranch} branch.")
                            ->success()
                            ->send();
                    }
                }),

            Action::make('check_for_updates')
                ->label('Check for Updates')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear the cache to force a fresh check
                    cache()->forget('standalone_updater_latest_version');
                    cache()->forget('standalone_updater_latest_version_composer_json');

                    // Refresh the data
                    $updaterHelper = app(UpdaterHelper::class);
                    $this->latestVersion = $updaterHelper->getLatestVersion($this->selectedBranch);

                    if (\Composer\Semver\Comparator::greaterThan($this->latestVersion, $this->currentVersion)) {
                        $this->updateAvailable = true;
                        Notification::make()
                            ->title('Update available')
                            ->body("A new version ({$this->latestVersion}) is available for download from the {$this->selectedBranch} branch.")
                            ->success()
                            ->send();
                    } else {
                        $this->updateAvailable = false;
                        Notification::make()
                            ->title('No updates available')
                            ->body("You are running the latest version from the {$this->selectedBranch} branch.")
                            ->success()
                            ->send();
                    }
                }),

            Action::make('update_now')
                ->label('Update Now')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->url(fn () => route('api.updater.update-now', ['version' => $this->selectedBranch]))
                ->visible(fn () => $this->updateAvailable && $this->canUpdate),

            Action::make('reinstall')
                ->label('Reinstall')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->url(fn () => route('api.updater.update-now', ['version' => $this->selectedBranch]))
                ->visible(fn () => !$this->updateAvailable && $this->canUpdate),

            Action::make('copy_standalone')
                ->label('Copy Standalone Updater')
                ->icon('heroicon-o-document-duplicate')
                ->color('success')
                ->action(function () {
                    $updaterHelper = app(UpdaterHelper::class);
                    $updaterHelper->copyStandaloneUpdater();

                    Notification::make()
                        ->title('Standalone updater copied')
                        ->body('The standalone updater has been copied to the public directory.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
