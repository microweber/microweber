<?php

namespace Modules\Updater\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
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
    public $branches = [];

    public function mount(): void
    {
        $this->currentVersion = MW_VERSION;
        $this->selectedBranch = config('modules.updater.branch') ?? 'master';
        $this->branches = config('modules.updater.branches') ?? ['master' => 'Master'];
        
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

    public function changeBranch($branch)
    {
        $this->selectedBranch = $branch;

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
    }

    public function startUpdate()
    {
        return redirect()->route('api.updater.update-now', [
            'version' => $this->selectedBranch,
            'branch' => $this->selectedBranch
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
