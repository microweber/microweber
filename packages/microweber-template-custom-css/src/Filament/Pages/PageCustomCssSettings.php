<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\TemplateCustomCss\Exceptions\InvalidCssException;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;

/**
 * Filament admin page for editing PER-PAGE custom CSS + JS keyed by content id.
 *
 * The manual sibling of {@see TemplateCustomCssSettings} (which edits the GLOBAL,
 * site-wide live_edit layer) and of the AI's apply_page_css / apply_page_js tools.
 * Pick a page, edit its per-content-id CSS/JS; on save it is written to
 * userfiles/css/pages/live_edit_<id>.{css,js}, mirrored to the remote disk, and
 * served as the FINAL stylesheet / deferred footer script on that page only — so
 * it overrides the site-wide design there.
 *
 * Registered with the admin panel via FilamentRegistry::registerPage() in
 * TemplateCustomCssServiceProvider (the CMS auto-discovers no package pages).
 *
 * @property \Filament\Schemas\Schema $form
 */
class PageCustomCssSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 59;

    protected static ?string $title = 'Page Custom CSS / JS';

    protected static ?string $slug = 'page-custom-css-settings';

    protected string $view = 'template-custom-css::filament.pages.page-custom-css-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'content_id' => null,
            'page_css' => '',
            'page_js' => '',
            'css_url' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Page')
                    ->description('Choose the page whose custom CSS / JS you want to edit. Changes apply to this page only (scoped by its content id).')
                    ->schema([
                        Select::make('content_id')
                            ->label('Page')
                            ->options(fn (): array => $this->pageOptions())
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state): void {
                                $this->loadPage(($state === null || $state === '') ? null : (int) $state);
                            }),

                        TextInput::make('css_url')
                            ->label('Served CSS URL')
                            ->readOnly()
                            ->placeholder('— nothing saved for this page yet —')
                            ->visible(fn (): bool => !empty($this->data['content_id'])),
                    ]),

                Section::make('Page CSS')
                    ->description('Loaded after the site-wide design, so it overrides the global look on this page only.')
                    ->visible(fn (): bool => !empty($this->data['content_id']))
                    ->schema([
                        Textarea::make('page_css')
                            ->label('Custom CSS for this page')
                            ->rows(16)
                            ->extraInputAttributes(['class' => 'font-mono text-sm']),
                    ]),

                Section::make('Page JS')
                    ->description('Runs on the PUBLISHED page only (never inside the editor). Plain JavaScript, no <script> tags.')
                    ->visible(fn (): bool => !empty($this->data['content_id']))
                    ->schema([
                        Textarea::make('page_js')
                            ->label('Custom JavaScript for this page')
                            ->rows(12)
                            ->extraInputAttributes(['class' => 'font-mono text-sm']),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $formData = $this->form->getState();

        $contentId = (int) ($formData['content_id'] ?? 0);
        if ($contentId <= 0) {
            Notification::make()
                ->title('Pick a page first')
                ->warning()
                ->send();

            return;
        }

        $manager = app(TemplateCustomCssManager::class);
        $css = is_string($formData['page_css'] ?? null) ? $formData['page_css'] : '';
        $js = is_string($formData['page_js'] ?? null) ? $formData['page_js'] : '';

        try {
            $manager->liveEdit()->savePageCssContent($contentId, $css);
            $manager->liveEdit()->savePageJsContent($contentId, $js);
        } catch (InvalidCssException $e) {
            Notification::make()
                ->title('Invalid CSS')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return;
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Unable to save')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Page CSS / JS saved')
            ->success()
            ->send();

        $this->data['css_url'] = $manager->liveEdit()->getPageCssUrl($contentId);
    }

    protected function loadPage(?int $contentId): void
    {
        if ($contentId === null || $contentId <= 0) {
            $this->data['page_css'] = '';
            $this->data['page_js'] = '';
            $this->data['css_url'] = null;

            return;
        }

        $le = app(TemplateCustomCssManager::class)->liveEdit();

        $this->data['page_css'] = $le->getPageCssContent($contentId);
        $this->data['page_js'] = $le->getPageJsContent($contentId);
        $this->data['css_url'] = $le->getPageCssUrl($contentId);
    }

    /**
     * Pages/posts/products the admin can attach per-page CSS to, labelled
     * "Title (type #id)". Queried straight off the content table (no model
     * dependency) so the package stays usable outside the CMS; capped so the
     * picker stays responsive on large sites.
     *
     * @return array<int, string>
     */
    protected function pageOptions(): array
    {
        try {
            $rows = DB::table('content')
                ->where('is_deleted', 0)
                ->whereIn('content_type', ['page', 'post', 'product'])
                ->orderByDesc('updated_at')
                ->limit(500)
                ->get(['id', 'title', 'content_type']);
        } catch (\Throwable) {
            return [];
        }

        $options = [];
        foreach ($rows as $row) {
            $id = (int) ($row->id ?? 0);
            if ($id <= 0) {
                continue;
            }
            $title = trim((string) ($row->title ?? '')) ?: 'Untitled';
            $type = (string) ($row->content_type ?? 'content');
            $options[$id] = $title . ' (' . $type . ' #' . $id . ')';
        }

        return $options;
    }
}
