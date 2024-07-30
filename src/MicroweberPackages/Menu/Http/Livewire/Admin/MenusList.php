<?php

namespace MicroweberPackages\Menu\Http\Livewire\Admin;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\Menu\Models\Menu;
use function Clue\StreamFilter\fun;

class MenusList extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $menu_id = 0;

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('menu_id')
                ->live()
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
            });
    }

    public function addMenuItemAction(): Action
    {
        return CreateAction::make('addMenuItemAction')
            ->modalWidth('md')
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

    public function createAction(): Action
    {
        return CreateAction::make('create')
            ->label('Add menu')
            ->form([
                TextInput::make('title')
                     ->required()
                    ->maxLength(255),
            ])
            ->action(function (array $data) {

                $data['item_type'] = 'menu';

                $record = Menu::newModelInstance();
                $record->fill($data);
                $record->save();

            });
    }

    public static function menuItemEditFormArray() : array
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
                ->afterStateUpdated(function (Menu | null $record, Set $set, $state) {
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
                ->selectedData(function (Menu | null $record, Get $get) {
                    $dataId = '';
                    $dataType = '';
                    $dataUrl = '';
                    $dataTarget = '';
                    if ($record) {
                        $dataUrl = $record->url;
                        $dataTarget = $record->url_target;
                        if ($record->content_id) {
                            $dataId = $record->content_id;
                            $dataType = 'content';
                        } else if ($record->categories_id) {
                            $dataId = $record->categories_id;
                            $dataType = 'category';
                        }
                    }
                    $data = [
                        'url'=> $dataUrl,
                        'target'=> $dataTarget,
                        'data'=>[
                            'id'=> $dataId,
                            'type'=> $dataType
                        ]
                    ];

                    return $data;
                })
                ->afterStateUpdated(function (Set $set, Get $get, array $state) {

                    $url = '';
                    $urlTarget = '';
                    $categoriesId = '';
                    $contentId = '';
                    $displayTitle = $get('display_title');

                    if (isset($state['data']['id']) && $state['data']['id'] > 0) {
                        if ($state['data']['type'] == 'category') {
                            $categoriesId = $state['data']['id'];
                        } else {
                            $contentId = $state['data']['id'];
                        }
                    } else if (isset($state['url'])) {
                        $url = $state['url'];
                        $urlTarget = $state['url_target'];
                    }
                    if (isset($state['text'])) {
                        $displayTitle = $state['text'];
                    }

                    $set('display_title', $displayTitle);
                    $set('url', $url);
                    $set('url_target', $urlTarget);
                    $set('categories_id', $categoriesId);
                    $set('content_id', $contentId);
                }),

        ];
    }

    public function editAction(): Action
    {
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

                $form->fill($recordArray);
            })
            ->modalAutofocus(false)
            ->form(static::menuItemEditFormArray())->record(function (array $arguments) {
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

            $this->menu_id = $findFirstMenu->id;
        }

    }

    public function render(): View
    {
        return view('menu::livewire.admin.menus-list', [
            'menu' => Menu::where('item_type', 'menu')
                ->where('id', $this->menu_id)
                ->first()
        ]);
    }
}
