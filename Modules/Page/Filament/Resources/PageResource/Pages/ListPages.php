<?php

namespace Modules\Page\Filament\Resources\PageResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Modules\Content\Models\Content;
use Modules\Page\Filament\Resources\PageResource;
use Modules\Page\Models\Page;

class ListPages extends \Modules\Content\Filament\Admin\ContentResource\Pages\ListContents
{
    protected static string $resource = PageResource::class;

    /**
     * task-2026-05-17-0b912e / AI-736 surface 2 — hide the
     * paginator chrome when the page count is ≤ 10. The parent
     * ContentResource::table() declares
     *   ->paginated([10, 25, 50, 100, 250, 'all'])
     *   ->defaultPaginationPageOption(250)
     * which renders the "Per page 250" dropdown even when only
     * 1 row exists. Override here for the Pages list only —
     * Posts / Products / Content still inherit the parent
     * pagination because their row-counts vary more.
     *
     * Count is computed once per render — cheap on /admin/pages
     * because the rowset is bounded (typical site has <50 pages).
     */
    public function table(Table $table): Table
    {
        $table = parent::table($table);

        $pageCount = Content::where('content_type', 'page')
            ->where(function ($q) { // task-2026-05-22-3b90dd AI-876: deleted_at does not exist on content table; is_deleted is the soft-delete column
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->count();

        // task-2026-05-22-e0571b / AI-736 re-dispatch: AC#4 — for >10 pages the
        // parent defaultPaginationPageOption(250) still renders "Per page 250".
        // Override to a sensible [10,25,50] set defaulting to 25.
        if ($pageCount <= 10) {
            $table->paginated(false);
        } else {
            $table->paginated([10, 25, 50])->defaultPaginationPageOption(25);
        }

        return $table;
    }

    protected function getHeaderActions(): array
    {
        $actions = parent::getHeaderActions();

        // task-2026-05-17-0b912e / AI-736 — prominent "+ Add page"
        // CTA top-right per designer dispatch 2026-05-16T15:41:02.
        // ContentResource ListContents intentionally removed the
        // in-resource CreateAction in cycle-61 / AI-39 in favour of
        // the global "+ Add New" topbar dropdown. Designer's audit
        // (AI-736) reverses that decision for Pages specifically:
        // Pages is the primary content type in a website builder,
        // the global dropdown is too remote, and the audit confirmed
        // first-time-user discoverability failure. Surface 1 of
        // AI-736's 3 compounding issues. Surfaces 2 (paginator
        // hide-when-≤10) and 3 (collapse row actions to ≤3) tracked
        // as AI-736a / AI-736b follow-ups.
        $actions[] = Actions\CreateAction::make()
            ->label('+ Add page')
            ->icon('heroicon-o-plus')
            ->color('primary');

        // Check if there's no homepage set
        $hasHomepage = Content::where('content_type', 'page')
            ->where('is_home', 1)
            ->exists();

        $hasPages = Content::where('content_type', 'page')
            ->exists();

        if (!$hasHomepage) {
            $actions[] = Actions\Action::make('setup_homepage')
                ->visible($hasPages)
                ->label('Setup Homepage')
                ->icon('heroicon-o-home')
                ->color('warning')
                ->modalHeading('Select a page to be your homepage')
                ->modalDescription('Choose which page should be displayed as your website\'s homepage.')
                ->slideOver()
                ->form([
                    Forms\Components\Select::make('page_id')
                        ->label('Select Page')
                        ->placeholder('Choose a page to set as homepage')
                        ->options(function () {
                            return Page::where('content_type', 'page')
                                ->where('is_active', 1)
                                ->pluck('title', 'id')
                                ->toArray();
                        })
                        ->required()
                        ->searchable()
                        ->helperText('Select an active page to set as your homepage. Only published pages are shown.')
                ])
                ->action(function (array $data) {
                    // First, unset any existing homepage
                    Content::where('is_home', 1)->update(['is_home' => 0]);

                    // Set the selected page as homepage
                    $page = Content::find($data['page_id']);
                    if ($page) {
                        $page->update(['is_home' => 1]);

                        Notification::make()
                            ->title('Homepage Set Successfully')
                            ->body("'{$page->title}' has been set as your homepage.")
                            ->success()
                            ->send();
                    }
                });
        }

        return $actions;
    }
}
