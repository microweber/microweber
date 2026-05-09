<?php

namespace Modules\Menu\Livewire\Admin;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Size;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\Multilanguage\Filament\Resources\Concerns\TranslatableResource;
use MicroweberPackages\Multilanguage\Forms\Actions\TranslateFieldAction;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Menu\Models\Menu;

class MenusList extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    use TranslatableResource;


    public int $menu_id = 0;
    public string $option_group = ''; //if this is set it will save as module option on change
    public string $option_key = ''; //if this is set it will save as module option on change

    protected static function findMenuOrFail($id): ?Menu
    {
        return Menu::find($id);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('menu_id')
                ->live()
                ->native(true)
                ->selectablePlaceholder(false)
                ->default(fn (Get $get) => $get('menu_id'))
                ->options(function () {
                    return Menu::where('item_type', 'menu')
                        ->orderByRaw("CASE WHEN title IN ('header_menu','footer_menu_1','footer_menu_2','footer_menu_3') THEN 0 ELSE 1 END")
                        ->orderBy('id')
                        ->pluck('title', 'id');
                })
                ->label('Menu'),
        ]);
    }

    public function deleteAction(): Action
    {
        // Icon-only Delete button for per-item rows — full-width
        // text+icon was hiding the menu-item title on the narrow
        // 360px live-edit panel (task-2026-04-30-d86bb9).
        return Action::make('delete')
            ->label('Delete')
            ->icon('heroicon-m-trash')
            ->iconButton()
            ->tooltip('Delete')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $record = static::findMenuOrFail($arguments['id']);
                if ($record) {
                    if ($record->item_type === 'menu') {
                        Menu::where('parent_id', $record->id)
                            ->where('item_type', 'menu_item')
                            ->delete();
                    }
                    $record->delete();
                }
                $this->dispatch('$refresh');
            });
    }

    public function deleteMenuAction(): Action
    {
        // Text+icon Delete for the 3-dot ActionGroup dropdown.
        // No ->iconButton() so the label is always visible in the dropdown.
        return Action::make('deleteMenu')
            ->label('Delete menu')
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $record = static::findMenuOrFail($arguments['id']);
                if ($record) {
                    if ($record->item_type === 'menu') {
                        Menu::where('parent_id', $record->id)
                            ->where('item_type', 'menu_item')
                            ->delete();
                    }
                    $record->delete();
                }
                $this->dispatch('$refresh');
            });
    }

    public function renameMenuAction(): Action
    {
        return Action::make('renameMenu')
            ->label('Rename menu')
            ->icon('heroicon-m-pencil-square')
            ->mountUsing(function (Schema $schema, array $arguments) {
                $record = static::findMenuOrFail($arguments['id']);
                if ($record) {
                    $schema->fill(['title' => $record->title]);
                }
            })
            ->form([
                TextInput::make('title')
                    ->required()
                    ->maxLength(200)
                    ->label('Menu name'),
            ])
            ->action(function (array $data, array $arguments) {
                $record = static::findMenuOrFail($arguments['id']);
                if ($record) {
                    $record->title = $data['title'];
                    $record->save();
                }
            });
    }

    public function addMenuItemAction(): Action
    {
        return CreateAction::make('addMenuItemAction')
            ->createAnother(false)
            // cycle-N (post-cycle-116 modal-fit fix): the parent
            // MenuModuleSettings page is itself a slide-over — when
            // this action ALSO uses ->slideOver() it tries to mount
            // a slide-over inside a slide-over, which Filament
            // collapses into a centered modal that gets clipped
            // below the viewport (no Save/Cancel visible). Switching
            // to a normal centered modal with an explicit medium
            // width gives the user a fully-visible Create dialog
            // with sticky footer actions.
            ->modalWidth(\Filament\Support\Enums\Width::Medium)
            ->modalAutofocus(false)
            ->modalSubmitActionLabel(__('Add menu item'))
            ->modalCancelActionLabel(__('Cancel'))
            ->mountUsing(function (Schema $schema, array $arguments) {
                $schema->fill($arguments);
            })
            ->label('Add menu item')
            ->icon('heroicon-m-plus')
            ->form(static::menuItemEditFormArray())
            ->action(function (array $data) {

                $data['item_type'] = 'menu_item';
                $data['parent_id'] = $this->menu_id;

                $record = new Menu();
                $record->fill($data);
                $record->save();

                if ($this->option_group != '' and $this->option_key != '') {
                    $this->dispatch('mw-option-saved',
                        optionGroup: $this->option_group,
                        optionKey: $this->option_key,
                    );
                }
            });
    }

    protected $listeners = [
        'newMenuAdded' => '$refresh',
        'menuOrderUpdated' => 'onMenuOrderUpdated',
    ];

    public function onMenuOrderUpdated($menuId)
    {
        if ($this->option_group != '' and $this->option_key != '') {
            $this->dispatch('mw-option-saved',
                optionGroup: $this->option_group,
                optionKey: $this->option_key,

            );
        }
    }

    public function createAction(): Action
    {
        return CreateAction::make('create')
            ->label('Create new main menu')
            ->createAnother(false)
            ->form([
                TextInput::make('title')
                    ->required()
                    ->maxLength(200),
            ])
            ->action(function (array $data, Component $livewire) {

                $data['item_type'] = 'menu';

                $record = Menu::newModelInstance();
                $record->fill($data);
                $record->save();

                $livewire->menu_id = $record->id;
                // $this->menu_id = $record->id;
                // $this->dispatch('newMenuAdded');

            });
    }

    public static function menuItemEditFormArray(): array
    {

        $isMultilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();

        return [


            Hidden::make('content_id'),
            Hidden::make('categories_id'),
            Hidden::make('url'),
            Hidden::make('url_target'),

            Hidden::make('multilanguage')
                ->visible($isMultilanguageEnabled)
            ,


            MwLinkPicker::make('mw_link_picker')
                ->label('Link')
                // AI-72 / TICKET-AC (cycle-85 2026-05-09): debounce
                // ->live() so every keystroke doesn't fire a
                // Livewire roundtrip. The link picker's
                // afterStateUpdated callback persists the URL +
                // resets content_id/categories_id — running it on
                // every char of typed text wastes round-trips and
                // in practice causes the protocol allow-list regex
                // to clear half-typed URLs (`https`, `https:`,
                // `https:/` all fail the http(s)?:// check) and
                // leave the user staring at an empty field.
                ->live(debounce: 400)
                ->selectedData(function (Menu|null $record, Get $get) {
                    $dataId = '';
                    $dataType = '';
                    $dataUrl = '';
                    $dataTarget = '';
                    if ($record) {
                        $dataUrl = $record->url;
                        $dataTarget = $record->url_target;
                        if ($record->content_id) {
                            $getContent = get_content_by_id($record->content_id);
                            $dataId = $record->content_id;
                            $dataType = $getContent['content_type'] ?? 'content';
                            $dataUrl = content_link($record->content_id);
                        } else if ($record->categories_id) {
                            $dataId = $record->categories_id;
                            $dataType = 'category';
                        }
                    }
                    $data = [
                        'url' => $dataUrl,
                        'target' => $dataTarget,
                        'data' => [
                            'id' => $dataId,
                            'type' => $dataType
                        ]
                    ];
                    return $data;
                })
                ->afterStateUpdated(function (Set $set, Get $get, array $state, ?Model $record = null, $livewire = null) {

                    // Don't touch the title field here — every Livewire
                    // commit fires this callback (because mw_link_picker
                    // is ->live()), and any $set('title', ...) call
                    // races against the user's in-progress typing in
                    // the Title TextInput, which is also ->live().
                    // Per task-2026-04-30-48b603 the user reported the
                    // Title field swallowed keystrokes — that's because
                    // this callback was overwriting the typed value
                    // back to whatever $displayTitle the link picker
                    // resolved to (often empty for not-yet-changed
                    // page links). Title is now purely user-controlled;
                    // this callback only mirrors the structural
                    // url/url_target/content_id/categories_id fields.

                    $url = '';
                    $urlTarget = '';
                    $categoriesId = '';
                    $contentId = '';

                    if (isset($state['data']['id']) && $state['data']['id'] > 0) {
                        if ($state['data']['type'] == 'category') {
                            $categoriesId = $state['data']['id'];
                        } else {
                            $contentId = $state['data']['id'];
                        }
                    } else if (isset($state['url'])) {
                        $url = $state['url'];
                        if (isset($state['target']) && $state['target']) {
                            $urlTarget = $state['target'];
                        }
                    }

                    // audit-test 2026-05-07 Menu Link Picker audit finding #2 (SECURITY)
                    // + post-merge follow-up #3 (TICKET-AS):
                    // - Cycle-24 added the protocol allow-list to block
                    //   `javascript:alert(...)` etc.
                    // - Follow-up #3: the leading `/` branch also matched
                    //   `//attacker.com/path` (protocol-relative URL) so an
                    //   attacker could still set the menu link to an external
                    //   origin. Tightened the slash-branch to `/(?!/)` so
                    //   only SINGLE-leading-slash root-relative paths pass.
                    if ($url !== '' && ! preg_match('#^(https?://|/(?!/)|\#|mailto:|tel:)#i', $url)) {
                        $url = '';
                    }

                    $set('url', $url);
                    $set('url_target', $urlTarget);
                    $set('categories_id', $categoriesId);
                    $set('content_id', $contentId);

                    if ($record) {
                        $record->url = $url;
                        $record->url_target = $urlTarget;
                        $record->categories_id = $categoriesId ?: null;
                        $record->content_id = $contentId ?: null;
                        $record->save();
                    }

                    if ($livewire && $livewire->option_group != '') {
                        $livewire->dispatch('mw-option-saved',
                            optionGroup: $livewire->option_group,
                            optionKey: $livewire->option_key,
                        );
                    }

                }),


            // AI-72 / TICKET-AC (cycle-85 2026-05-09): dead-fields
            // sweep — three commented-out form blocks deleted from
            // this method:
            //   - TextInput::make('display_title')        — superseded
            //     by the active `title` field; the original "display
            //     title vs custom title" feature was rejected during
            //     cycle-44 EVAL (task-2026-04-30-48b603 confirmed the
            //     UX simplification).
            //   - Checkbox::make('use_custom_title')      — partner
            //     to display_title; both removed together.
            //   - Select::make('url_target')              — superseded
            //     by the live Toggle('url_target') below; the legacy
            //     Select offered _self/_blank/_parent/_top but
            //     real-world usage is binary (current vs new window).
            //
            // Removing these blocks dropped 35 commented-out lines
            // from the form definition and removes confusion for
            // future reviewers about which field is canonical.

            TextInput::make('title')
                ->helperText('Set the title of the menu item.')
                ->required()
                // AI-72 / TICKET-AC (cycle-85 2026-05-09): debounce
                // typing so we don't roundtrip on every keystroke.
                // 500ms matches the cycle-50 newsletter-list pattern
                // (after-keystroke pause that feels instant to the
                // user without flooding the server).
                ->live(debounce: 500)
                ->reactive()
                ->extraInputAttributes([
                    // AI-72 / TICKET-AC (cycle-85): a11y — aria-describedby
                    // ties the helperText to the input so screen
                    // readers announce the help string when the user
                    // focuses the field, not just on mouse-hover.
                    'aria-describedby' => 'menu-item-title-help',
                ])
                ->afterStateUpdated(function (Get $get, Set $set, ?string $state, ?Model $record, $component, $livewire) {
                    // cycle-N (post-cycle-116 modal-fit fix follow-up):
                    // `$record` is nullable. On the EDIT path Filament
                    // passes the loaded Menu model; on the CREATE path
                    // (Add menu item dialog) the record doesn't exist
                    // yet and Filament passes null. The original
                    // signature `Model $record` (non-nullable) crashed
                    // the create form with a TypeError on the first
                    // keystroke. The body already had a `if ($record)`
                    // null-check so only the type-hint needed widening.
                    if ($record) {
                        $record->title = $state;
                        $record->save();
                    }

                    if ($livewire instanceof \Modules\Menu\Livewire\Admin\MenusList
                        && $livewire->option_group != ''
                    ) {
                        $livewire->dispatch('mw-option-saved',
                            optionGroup: $livewire->option_group,
                            optionKey: $livewire->option_key,
                        );
                    }

                })
                 ->hintAction(
                     TranslateFieldAction::make('title')->label('')
                 )
                ->maxLength(1255),


            Checkbox::make('advanced')
                ->label('Advanced')
                ->live(),


            Toggle::make('url_target')
                ->helperText('Enable to open the link in a new window.')
                // AI-72 / TICKET-AC (cycle-85 2026-05-09): toggle is
                // a single-click affordance — no debounce needed.
                ->live()
                ->label('Open link in new window')
                ->default(false)
                ->extraAttributes([
                    // a11y — describe the on/off semantic to AT users.
                    'aria-describedby' => 'menu-item-target-help',
                ])
                ->hidden(function (Get $get) {
                    return $get('advanced') === false;
                })
                ->columnSpanFull(),

            Group::make([
                MwFileUpload::make('default_image')
                    ->label('Default image')
                    ->hidden(function (Get $get) {
                        return $get('advanced') === false;
                    }),

                MwFileUpload::make('rollover_image')
                    ->label('Rollover image')
                    ->hidden(function (Get $get) {
                        return $get('advanced') === false;
                    }),
            ])->hidden(true)
                ->columns(2)
        ];
    }

    public function editAction(): Action
    {
        $menuTemplates = [];
        $scanTemplates = new \MicroweberPackages\Microweber\Support\ScanForBladeTemplates();
        $templatesForModule = $scanTemplates->scan('modules.menu::mega_menu_templates.menu_item');
        if ($templatesForModule) {
            foreach ($templatesForModule as $template) {
                $menuTemplates[$template['layout_file']] = $template['name'];
            }
        }

        // Icon-only Edit button with hover tooltip — see deleteAction
        // for the rationale (task-2026-04-30-d86bb9).
        return Action::make('edit')
            ->label('Edit')
            ->icon('heroicon-m-pencil')
            ->iconButton()
            ->tooltip('Edit')
            ->slideOver()
            ->mountUsing(function (Schema $schema, array $arguments) {
                $record = static::findMenuOrFail($arguments['id']);

                $recordArray = $record->toArray();
                $recordArray['display_title'] = $record->displayTitle;

                $recordArray['use_custom_title'] = false;
                if (!empty($record->title)) {
                    $recordArray['use_custom_title'] = true;
                }
                if (!empty($record->default_image) || !empty($record->rollover_image)) {
                    $recordArray['advanced'] = true;
                }

                $schema->fill($recordArray);
            })
            ->modalAutofocus(false)
            ->form(array_merge(
                self::menuItemEditFormArray(),
                [
                    Checkbox::make('enable_mega_menu')
                        ->label('Enable Mega Menu')
                        ->live()
                        ->hidden(true) //todo remove this when mega menu is ready
                        ->default(false),

                    Select::make('menu_item_template')
                        ->hidden(function (Get $get) {
                            return $get('enable_mega_menu') === false;
                        })
                        ->label('Mega Menu Template')
                        ->options($menuTemplates)
                        ->default('default'),
                ]
            ))
            ->record(function (array $arguments) {
                $record = static::findMenuOrFail($arguments['id']);
                return $record;
            })
            ->action(function (Menu $record, array $data) {

                // Don't apply the legacy use_custom_title→empty-title
                // wipe — the Checkbox that controlled it is commented
                // out (lines 302-312 above), so the flag is always
                // whatever mountUsing seeded it with (false for
                // menu items whose title was already empty). With no
                // way for the user to flip it from the form, the
                // wipe silently dropped every typed-in title — the
                // exact bug from task-2026-04-30-b4b3bd. The user
                // intent is now simple: whatever's in the Title
                // field gets saved verbatim.
                unset($data['use_custom_title']);
                $record->fill($data);
                $record->save();

                if ($this->option_group != '' and $this->option_key != '') {
                    $this->dispatch('mw-option-saved',
                        optionGroup: $this->option_group,
                        optionKey: $this->option_key,

                    );
                }
            });
    }

    public function mount()
    {
        if ($this->menu_id == 0) {
            $findFirstMenu = Menu::where('item_type', 'menu')
                ->first();

            if ($findFirstMenu) {
                $this->menu_id = $findFirstMenu->id;
            }
        }

    }

    public function updatedMenuId($value)
    {

        if ($this->option_group != '' and $this->option_key != '') {
            $menu = get_menus('one=1&limit=1&id=' . $value);
            $title = '';
            if ($menu) {
                $title = $menu['title'];
            }
            // $this->menu_id = $menu['id'] ?? 0;
            //save_option($this->option_key, $title, $this->option_group);
            $module = 'menu';
            $optionKey = $this->option_key;
            $group = $this->option_group;


            save_module_option($optionKey, $title, $group, $module);


            $this->dispatch('mw-option-saved',
                optionGroup: $this->option_group,
                optionKey: $this->option_key,
                optionValue: $title
            );
        }

    }

    public function menuActionsGroup(): ActionGroup
    {
        return ActionGroup::make([
            $this->addMenuItemAction()->arguments(['parent_id' => $this->menu_id]),
            $this->renameMenuAction()->arguments(['id' => $this->menu_id]),
            $this->createAction(),
            $this->deleteMenuAction()->arguments(['id' => $this->menu_id]),
        ])
            ->label('Menu actions')
            ->icon('heroicon-m-ellipsis-vertical')
            ->size(Size::Small)
            ->color('gray');
    }

    public function render(): View
    {
        $firstMenu = Menu::where('item_type', 'menu')
            ->where('id', $this->menu_id)
            ->first();

        if (!$firstMenu) {
            $firstMenu = Menu::where('item_type', 'menu')
                ->first();
            if ($firstMenu) {
                $this->menu_id = $firstMenu->id;
            }
        }

        return view('modules.menu::livewire.admin.menus-list', [
            'menu' => $firstMenu
        ]);
    }
}
