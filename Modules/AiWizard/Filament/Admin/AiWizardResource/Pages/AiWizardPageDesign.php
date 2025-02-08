<?php

namespace Modules\AiWizard\Filament\Admin\AiWizardResource\Pages;

use Filament\Resources\Pages\Page;
use Modules\AiWizard\Filament\Admin\AiWizardResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Content\Models\Content;

class AiWizardPageDesign extends Page
{
    use InteractsWithRecord;

    protected static string $resource = AiWizardResource::class;

    protected static string $view = 'modules.aiwizard::filament.pages.ai-wizard-page-design';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Edit')
                ->url(fn() => $this->getResource()::getUrl('edit', ['record' => $this->record]))
                ->color('gray'),
        ];
    }
}
