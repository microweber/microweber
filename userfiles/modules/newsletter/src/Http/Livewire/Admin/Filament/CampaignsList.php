<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\Filament;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;

class CampaignsList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public function render(): View
    {
        return view('microweber-module-newsletter::livewire.filament.admin.simple-table');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(NewsletterCampaign::query())
            ->paginated(false)
            ->heading('Campaigns lists')
            ->headerActions([])
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
