<?php

namespace Modules\Marketplace\Filament\Admin\MarketplaceResource\Pages;

use Filament\Actions;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Cache;
use Modules\Marketplace\Filament\Admin\MarketplaceResource;

class ListMarketplaces extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = MarketplaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reload-packages')
                ->label('Reload Packages')
                ->link()
                ->color('secondary')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear marketplace cache
                    Cache::forget('livewire-marketplace');
                    
                    Notification::make()
                        ->title('Marketplace Refreshed')
                        ->body('Package list has been refreshed from the marketplace.')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('licenses')
                ->label('Licenses')
                ->modal('licenses')
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->modalContent(view('modules.marketplace::filament.admin.show-list-licenses'))
                ->link()
                ->color('secondary')
                ->icon('heroicon-o-key'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Packages'),
            'templates' => Tab::make('Templates')
                ->query(fn ($query) => $query->where('type', 'microweber-template')),
            'modules' => Tab::make('Modules')
                ->query(fn ($query) => $query->where('type', 'microweber-module')),
            'installed' => Tab::make('Installed')
                ->query(fn ($query) => $query->where('has_current_install', 1)),
            'updates' => Tab::make('Updates Available')
                ->query(fn ($query) => $query->where('has_update', 1)),
        ];
    }

    public function mount(): void
    {
        parent::mount();
        
        // Check for flash messages from bulk operations
        if (session()->has('bulkInstallResults')) {
            $this->notifyBulkResults('Install', session('bulkInstallResults'));
        }
        
        if (session()->has('bulkUpdateResults')) {
            $this->notifyBulkResults('Update', session('bulkUpdateResults'));
        }
        
        if (session()->has('bulkUninstallResults')) {
            $this->notifyBulkResults('Uninstall', session('bulkUninstallResults'));
        }
        
        if (session()->has('success')) {
            Notification::make()
                ->title('Success')
                ->body(session('success'))
                ->success()
                ->send();
        }
        
        if (session()->has('error')) {
            Notification::make()
                ->title('Error')
                ->body(session('error'))
                ->danger()
                ->send();
        }
    }

    protected function notifyBulkResults(string $operation, array $results): void
    {
        $successCount = count(array_filter($results, fn ($r) => $r['status'] === 'success'));
        $errorCount = count(array_filter($results, fn ($r) => $r['status'] === 'error'));
        
        $messages = [];
        foreach ($results as $result) {
            if ($result['status'] === 'error') {
                $messages[] = "{$result['name']}: {$result['message']}";
            }
        }
        
        if ($errorCount > 0) {
            Notification::make()
                ->title("{$operation} Results")
                ->body("Success: {$successCount}, Failed: {$errorCount}")
                ->warning()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view_errors')
                        ->label('View Errors')
                        ->button()
                        ->modalContent(implode("\n", $messages))
                        ->modalSubmitAction(false),
                ])
                ->send();
        } else {
            Notification::make()
                ->title("{$operation} Complete")
                ->body("{$successCount} packages processed successfully.")
                ->success()
            ->send();
        }
    }
}
