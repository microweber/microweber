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
        // Icon-only Delete button with hover tooltip — full-width
        // text+icon was hiding the menu-item title on the narrow
        // 360px live-edit panel (task-2026-04-30-d86bb9). The
        // ->label('Delete') is preserved so screen readers still
        // announce the action; ->iconButton() drops the visible text.
        return Action::make('delete')
            ->label('Delete')
            ->icon('heroicon-m-trash')
            ->iconButton()
            ->tooltip('Delete')
            ->color('danger')
            ->slideOver()
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
            ->slideOver()
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
                ->live()
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
                ->afterStateUpdated(function (Set $set, Get $get, array $state) {


                    $url = '';
                    $urlTarget = '';
                    $categoriesId = '';
                    $contentId = '';
                    $title = $get('title');
                    //   $displayTitle = $get('display_title');

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
                        if (isset($state['text'])) {
                            $title = $state['text'];
                            //   $set('use_custom_title', true);
                        }
                    }
                    if (isset($state['text'])) {
                        $displayTitle = $state['text'];

                        if (!$title) {
                            $set('title', $displayTitle);
                        }
                        // $set('use_custom_title', false);
                    }
                    //    $set('display_title', $displayTitle);
                    //   $set('title', $title);
                    $set('url', $url);
                    $set('url_target', $urlTarget);
                    $set('categories_id', $categoriesId);
                    $set('content_id', $contentId);
                }),


            /*  TextInput::make('display_title') // This is the title that will be displayed in the menu
              ->label('Display title')
                  ->required()
                  ->live()
                  ->maxLength(255)
                  ->helperText('This is the title that will be displayed in the menu. If you want to use a custom title, check the "Use custom title" option below.'),*/


            TextInput::make('title')
                ->helperText('Set the title of the menu item.')
                ->required()
                // Per-keystroke live binding — user feedback in
                // task-2026-04-30-6d4a70 specifically asked to
                // remove the on-blur-only behaviour ("editing only
                // works when I press enter, but I want to edit it
                // in real time"). Livewire applies its default
                // debounce (~150ms) so per-keystroke does NOT
                // hammer the server on every keypress; it batches
                // rapid input.
                ->live(debounce: 300)
                ->hintAction(
                    TranslateFieldAction::make('title')->label('')
                )
                ->maxLength(1255),

//            Checkbox::make('use_custom_title')
//                ->label('Use custom title')
//                ->live()
//                ->afterStateUpdated(function (Menu|null $record, Set $set, $state) {
//                    if ($state) {
//                        if ($record) {
//                          //  $set('title', $record->displayTitle);
//                        }
//                    }
//                })
//                ->default(false),


            Checkbox::make('advanced')
                ->label('Advanced')
                ->live(),


            Toggle::make('url_target')
                ->helperText('Enable to open the link in a new window.')
                ->live()
                ->label('Open link in new window')
                ->default(false)
                ->hidden(function (Get $get) {
                    return $get('advanced') === false;
                })
                ->columnSpanFull(),

//            Select::make('url_target')
//                ->label('Target attribute')
//                ->helperText('Open the link in New window, Current window, Parent window or Top window')
//                ->options([
//                    '_self' => 'Current window',
//                    '_blank' => 'New window',
//                    '_parent' => 'Parent window',
//                    '_top' => 'Top window',
//                ])
//                ->hidden(function (Get $get) {
//                    return $get('advanced') === false;
//                }),

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
            $this->deleteAction()->arguments(['id' => $this->menu_id]),
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
