<?php

namespace Modules\Updater\Filament\Resources\UpdaterResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Modules\Updater\Filament\Resources\UpdaterResource;

class ManageUpdater extends ManageRecords
{
    protected static string $resource = UpdaterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('check_for_updates')
                ->label('Check for Updates')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear the cache to force a fresh check
                    cache()->forget('standalone_updater_latest_version');
                    cache()->forget('standalone_updater_latest_version_composer_json');
                    
                    // Redirect back to refresh the page
                    return redirect(request()->header('Referer'));
                }),
        ];
    }
}
