<?php

namespace Modules\Menu\Livewire\Admin;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use Modules\Menu\Models\Menu;

class MenusList extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public int $menu_id = 0;
    public string $option_group = ''; //if this is set it will save as module option on change
    public string $option_key = ''; //if this is set it will save as module option on change

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('menu_id')
                ->live()
                ->native(false)
                ->selectablePlaceholder(false)
                ->default(function (Component $component, Get $get) {

                    return $get('menu_id');
                })
                ->options(Menu::where('item_type', 'menu')->get()->pluck('title', 'id'))
                ->preload()
                ->label(' '),
        ]);
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $record = Menu::find($arguments['id']);
                $record?->delete();
                $this->dispatch('$refresh');
            });
    }

    public function addMenuItemAction(): Action
    {
        return CreateAction::make('addMenuItemAction')
//            ->modalWidth('md')
            ->mountUsing(function (Form $form, array $arguments) {
                $form->fill($arguments);
            })
            ->label('Add menu item')
            ->form(static::menuItemEditFormArray())
            ->action(function (array $data) {

                $data['item_type'] = 'menu_item';
                $data['parent_id'] = $this->menu_id;

                $record = new Menu();
                $record->fill($data);
                $record->save();


            });
    }

    protected $listeners = [
        'newMenuAdded' => '$refresh'
    ];

    public function createAction(): Action
    {
        return CreateAction::make('create')
            ->label('Add menu')
            ->createAnother(false)
            ->form([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
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
        return [

            TextInput::make('display_title')
                ->disabled()
                ->hidden(function (Get $get) {
                    return $get('use_custom_title') === true;
                }),

            TextInput::make('title')
                ->hidden(function (Get $get) {
                    return $get('use_custom_title') === false;
                })
                ->helperText('Title will be auto-filled from the selected content')
                ->maxLength(255),

            Checkbox::make('use_custom_title')
                ->label('Use custom title')
                ->live()
                ->afterStateUpdated(function (Menu|null $record, Set $set, $state) {
                    if ($state) {
                        if ($record) {
                            $set('title', $record->displayTitle);
                        }
                    }
                })
                ->default(false),

            Hidden::make('content_id'),
            Hidden::make('categories_id'),
            Hidden::make('url'),
            Hidden::make('url_target'),

            MwLinkPicker::make('mw_link_picker')
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
                    $displayTitle = $get('display_title');

                    if (isset($state['data']['id']) && $state['data']['id'] > 0) {
                        if ($state['data']['type'] == 'category') {
                            $categoriesId = $state['data']['id'];
                        } else {
                            $contentId = $state['data']['id'];
                        }
                        if (isset($state['text'])) {
                            $displayTitle = $state['text'];
                            $set('use_custom_title', false);
                        }
                    } else if (isset($state['url'])) {
                        $url = $state['url'];
                        if (isset($state['target']) && $state['target']) {
                            $urlTarget = $state['target'];
                        }
                        if (isset($state['text'])) {
                            $title = $state['text'];
                            $set('use_custom_title', true);
                        }
                    }

                    $set('display_title', $displayTitle);
                    $set('title', $title);
                    $set('url', $url);
                    $set('url_target', $urlTarget);
                    $set('categories_id', $categoriesId);
                    $set('content_id', $contentId);
                }),

            Checkbox::make('advanced')
                ->label('Advanced')
                ->live(),

            Select::make('url_target')
                ->label('Target attribute')
                ->helperText('Open the link in New window, Current window, Parent window or Top window')
                ->options([
                    '_self' => 'Current window',
                    '_blank' => 'New window',
                    '_parent' => 'Parent window',
                    '_top' => 'Top window',
                ])
                ->hidden(function (Get $get) {
                    return $get('advanced') === false;
                }),

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
            ])->columns(2)
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

        return Action::make('edit')
            ->icon('heroicon-m-pencil')
            ->mountUsing(function (Form $form, array $arguments) {
                $record = Menu::find($arguments['id']);
                $recordArray = $record->toArray();
                $recordArray['display_title'] = $record->displayTitle;

                $recordArray['use_custom_title'] = false;
                if (!empty($record->title)) {
                    $recordArray['use_custom_title'] = true;
                }
                if (!empty($record->default_image) || !empty($record->rollover_image)) {
                    $recordArray['advanced'] = true;
                }

                $form->fill($recordArray);
            })
            ->modalAutofocus(false)
            ->form([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Checkbox::make('enable_mega_menu')
                    ->label('Enable Mega Menu')
                    ->live()
                    ->default(false),
                Select::make('menu_item_template')
                    ->hidden(function (Get $get) {
                        return $get('enable_mega_menu') === false;
                    })
                    ->label('Mega Menu Template')
                    ->options($menuTemplates)
                    ->default('default'),

            ])
            ->record(function (array $arguments) {
                $record = Menu::find($arguments['id']);
                return $record;
            })
            ->action(function (Menu $record, array $data) {
                if (isset($data['use_custom_title']) && $data['use_custom_title'] == false) {
                    $data['title'] = '';
                }
                $record->fill($data);
                $record->save();
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

        if($this->option_group != '' and $this->option_key != ''){
            $menu = get_menus('one=1&limit=1&id=' . $value);
            $title = '';
            if($menu){
                $title = $menu['title'];
            }

            save_option($this->option_key, $title, $this->option_group);

            $this->dispatch('mw-option-saved',
                optionGroup:  $this->option_group,
                optionKey:  $this->option_key,
                optionValue:  $title
            );
        }

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
