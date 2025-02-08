<?php

namespace Modules\AiWizard\Filament\Admin\AiWizardResource\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Resources\Pages\Page;
use Modules\AiWizard\Filament\Admin\AiWizardResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Content\Models\Content;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class AiWizardPageDesign extends Page implements HasForms
{
    use InteractsWithRecord;
    use InteractsWithForms;

    protected static string $resource = AiWizardResource::class;

    protected static string $view = 'modules.aiwizard::filament.pages.ai-wizard-page-design';

    protected function getFormSchema(): array
    {

        $content_link = content_link($this->record->id);

        return [
            View::make('filament-forms::components.mw-render-template-preview-iframe')
                ->viewData([
                    'url' => $content_link,

                ])
                //  ->live()
                //   ->key('dynamicPreviewLayout')
                ->columnSpanFull()
        ];
    }

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
