<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;

/**
 * Visual Editor Page
 *
 * Provides a full-page visual drag-and-drop editor interface for content editing.
 *
 * @package MicroweberPackages\LiveEdit\Filament\Admin\Pages
 */
class VisualEditorPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static string | null $navigationLabel = 'Visual Editor';
    protected static ?string $slug = 'visual-editor';
    protected static ?int $navigationSort = 20;

    protected string $view = 'microweber-live-edit::filament.pages.visual-editor-page';

    public ?int $contentId = null;
    public ?string $contentType = 'page';

    public function mount(): void
    {
        $this->contentId = request()->query('content_id');
        $this->contentType = request()->query('content_type', 'page');
    }

    public function getViewData(): array
    {
        return [
            'contentId' => $this->contentId,
            'contentType' => $this->contentType,
        ];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function render(): View
    {
        return view($this->view, $this->getViewData())
            ->layout($this->getLayout(), [
                'maxContentWidth' => $this->getMaxContentWidth(),
                ...$this->getLayoutData(),
            ]);
    }
}
