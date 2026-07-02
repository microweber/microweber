<?php

namespace Modules\Content\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width as MaxWidth;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Models\Content;

class ContentTableList extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public string|null $moduleId = null;
    public string $contentModel = Content::class;
    public array $params = [];

    public function editFormArray($params = [])
    {
        // Use the lean live-edit form — this Livewire component
        // is mounted inside the per-module Items-list iframe in
        // the live-edit panel, so the same compact-modal logic
        // applies. task-2026-05-04-1d68c7.
        return ContentResource::formArrayCompact($params);

    }

    public function table(Table $table): Table
    {

        //  $query = Content::query()->where('is_active', 1);
        $query = $this->contentModel::query()->where('is_active', 1);

        return $table
            ->query($query)
            ->defaultSort('position', 'asc')
            ->columns([
                TextColumn::make('title')
                    ->limit(20)
                    ->action( EditAction::make('edit'))
                    ->label('Title'),
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                CreateAction::make('create')
                    // task-2026-05-17-2b1020 / AI-816 — primary CTA
                    // must render brand-blue (#0d6efd), not green.
                    // The earlier "match the green toolbar" rationale
                    // is obsolete: toolbar SAVE became a BLACK pill
                    // in AI-699. See CreateContent::getHeaderActions()
                    // for the full rationale.
                    ->color('primary')
                    // Centered modal (NOT a right-side slideOver) so the
                    // form gets the full-width column its title/url/
                    // content-body fields actually need. The previous
                    // slideOver looked cramped inside the
                    // post-module-settings iframe — see screenshot in
                    // task-2026-05-02-2ecfe6. Width matches the +ADD
                    // toolbar's generateAction modal (FiveExtraLarge,
                    // 1024px) for visual consistency between the two
                    // add-post entry points — bumped from 3xl in
                    // task-2026-05-04-61e974 along with the toolbar.
                    ->modalWidth(MaxWidth::FiveExtraLarge)
                    // `mw-content-form-modal` re-enables the close-overlay
                    // backdrop tint (the microweber-filament-theme
                    // globally forces fi-modal-close-overlay to
                    // bg-transparent). CSS lives in iframe-page.blade.php
                    // + live-edit-module-settings.blade.php.
                    ->extraModalWindowAttributes(['class' => 'mw-content-form-modal'])
                    // Don't let a stray backdrop click or Escape
                    // keystroke destroy a half-typed New Post form.
                    // task-2026-05-02-354958. Cancel still works via
                    // the X button or the explicit Cancel footer action.
                    ->closeModalByClickingAway(false)
                    ->closeModalByEscaping(false)
                    ->stickyModalFooter()
                    ->extraModalFooterActions([
                        // "Open in admin" escape hatch — the lean
                        // live-edit form skips SEO / Tags / Menus /
                        // Custom Fields. This button takes power
                        // users to the full admin create page in a
                        // new tab. task-2026-05-04-76275d.
                        \Filament\Actions\Action::make('openInAdmin')
                            ->label('Open in admin')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->color('gray')
                            ->url(ContentResource::getUrl('create'))
                            ->openUrlInNewTab(),
                    ])
                    ->form(function () {
                        $params = [];
                        $params['contentModel'] = $this->contentModel;
                        return $this->editFormArray($params);
                    })
                    ->after(fn () => $this->dispatchLiveEditCanvasRefresh())
            ])
            ->actions([
                EditAction::make('edit')
                    // task-2026-05-17-2b1020 / AI-816 — see CreateAction above.
                    ->color('primary')
                    ->modalWidth(MaxWidth::FiveExtraLarge)
                    ->extraModalWindowAttributes(['class' => 'mw-content-form-modal'])
                    ->closeModalByClickingAway(false)
                    ->closeModalByEscaping(false)
                    ->stickyModalFooter()
                    ->extraModalFooterActions([
                        // "Open in admin" escape hatch for editing —
                        // links to the full admin edit page so users
                        // can refine SEO / Custom Fields / Tags etc.
                        // task-2026-05-04-76275d.
                        \Filament\Actions\Action::make('openInAdmin')
                            ->label('Open in admin')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->color('gray')
                            ->url(fn (Model $record) => ContentResource::getUrl('edit', ['record' => $record]))
                            ->openUrlInNewTab(),
                    ])
                    ->form(function (Model $record) {

                        $params = [];
                        if ($record) {
                            $params['id'] = $record->id;
                            $params['contentModel'] = $this->contentModel;
                        }

                        return $this->editFormArray($params);
                        //  ->form()
                    })
                    ->after(fn () => $this->dispatchLiveEditCanvasRefresh()),
                DeleteAction::make('delete')
                    ->after(fn () => $this->dispatchLiveEditCanvasRefresh())
            ])
            ->reorderable('position')
            ->bulkActions([
                // BulkActionGroup::make([ DeleteBulkAction::make() ])
            ]);
    }

    public function render()
    {
        return view('modules.content::content-table-list');
    }

    /**
     * Dispatch a Livewire-bus event that the surrounding live-edit
     * iframe (rendered into /admin/post-module-settings or
     * /admin/products-module-settings etc.) intercepts and forwards
     * to its parent window. The parent's iframe-page Alpine listener
     * picks it up and calls `mw.app.canvas.refresh()` so the host
     * page's posts/products listing updates immediately after a
     * row is added/edited/deleted via the table actions —
     * task-2026-05-02-99f90c.
     *
     * Without this, the user added a post via "Edit Posts → New post",
     * the row landed in the DB, the slideOver iframe re-rendered the
     * table — but the page they were editing in the canvas behind the
     * slideOver showed the OLD post list because nothing told the
     * canvas to reload.
     */
    protected function dispatchLiveEditCanvasRefresh(): void
    {
        $this->dispatch('liveEditModuleTableActionSaved');
    }
}
