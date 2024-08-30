<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;

class Campaigns extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'newsletter/campaigns';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.campaigns';


    public function table(Table $table): Table
    {
        return $table
            ->query(NewsletterCampaign::query())
            ->paginated(false)
            ->heading('Campaigns')
            ->headerActions([
                CreateAction::make('create')
                    ->label('Start new campaign')
                    ->icon('heroicon-o-rocket-launch')
            ])
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('list.name'),
                TextColumn::make('subscribers'),
                TextColumn::make('scheduled'),
                TextColumn::make('scheduled_at'),
                TextColumn::make('done'),
            ]);
    }
}
