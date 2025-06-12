<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages;


use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use MicroweberPackages\LiveEdit\Filament\Actions\CustomViewAction;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\Modules\Logo\Http\Livewire\LogoModuleSettings;
use Modules\Category\Filament\Admin\Resources\CategoryResource;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Models\Content;
use function Clue\StreamFilter\fun;

class AdminLiveEditPage extends Page
{
    //   protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Live Edit';
    protected static string $description = '';
    protected static ?string $slug = 'live-edit';

    protected static ?int $navigationSort = 10;
    protected static string $view = 'microweber-live-edit::iframe-page';
    protected static string $layout = 'filament-panels::components.layout.live-edit';

    use InteractsWithActions;
    use InteractsWithForms;

    public function render(): View
    {
        $params = request()->all();
        return view($this->getView(), $this->getViewData())
            ->layout($this->getLayout(), [
                'livewire' => $this,
                'params' => $params,
                'maxContentWidth' => $this->getMaxContentWidth(),
                ...$this->getLayoutData(),
            ]);
    }


    public function addContentAction(): Action
    {
        $actions = [];
        $actions[] = [
            'title' => 'New Page',
            'description' => 'Create a new page to your website or online store, choose from pre-pared page designs ',
            'action' => 'addPageAction',
            'icon' => 'mw-add-page',
        ];
        $actions[] = [
            'title' => 'New Post',
            'description' => 'Add new post to your blog page, linked to category of main page on your website ',
            'action' => 'addPostAction',
            'icon' => 'mw-add-post',
        ];
        $actions[] = [
            'title' => 'New Category',
            'description' => 'Add new category and organize your blog posts or items from the shop in the right way ',
            'action' => 'addCategoryAction',
            'icon' => 'mw-add-category',
        ];
        $actions[] = [
            'title' => 'New Product',
            'description' => 'Add new product to your online store, choose from pre-pared product designs ',
            'action' => 'addProductAction',
            'icon' => 'mw-add-product',
        ];

        return Action::make('addContentAction')
            ->form([
                \Filament\Forms\Components\View::make('microweber-live-edit::add-content-modal')
                    ->viewData([
                        'actions' => $actions
                    ])
            ])
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->modalWidth(MaxWidth::Medium)
            ->slideOver();
    }

    public function addPageAction(): Action
    {
        return $this->generateAction('addPageAction', 'page');
    }

    public function addPostAction(): Action
    {
        return $this->generateAction('addPostAction', 'post');
    }

    public function addProductAction(): Action
    {
        return $this->generateAction('addProductAction', 'product');
    }

    public function addCategoryAction(): Action
    {
        return $this->generateAction('addCategoryAction', 'category');
    }

    public function openModuleSettingsAction(): Action
    {
        return Action::make('openModuleAction')
            ->modalIcon(function (array $arguments) {
                $data = $arguments['data'];
                if (isset($data['moduleSettingsComponent'])) {
                    if (isset($data['moduleSettingsComponent'])) {
                        $exists = class_exists($data['moduleSettingsComponent']);
                        if ($exists) {

                            /** @var LiveEditModuleSettings $resourceClass */
                            $resourceClass = $data['moduleSettingsComponent'];
                            if (method_exists($resourceClass, 'getNavigationIcon')) {
                                return $resourceClass::getNavigationIcon();
                            }
                        }
                    }
                }

                return 'mw-settings';
            })
            ->label(function (array $arguments) {
                $data = $arguments['data'];
                if (isset($data['moduleSettingsComponent'])) {
                    if (isset($data['moduleSettingsComponent'])) {
                        $exists = class_exists($data['moduleSettingsComponent']);
                        if ($exists) {
                            $resourceClass = $data['moduleSettingsComponent'];
                            /** @var LiveEditModuleSettings $resourceClass */
                            if (method_exists($resourceClass, 'getNavigationLabel')) {
                                return $resourceClass::getNavigationLabel();

                            }
                        }
                    }
                }

                return 'Module Settings';
            })
            //  ->modalContent(view('microweber::livewire.no-settings'))
            //->modalContent(view('microweber::livewire.no-settings'))
            ->form(
                function (array $arguments) {
                    $data = $arguments['data'];
                    $params = $data['params'];

                    if (isset($data['moduleSettingsComponent'])) {
                        $exists = livewire_component_exists($data['moduleSettingsComponent']);
                        if (!$exists) {
                            $exists = class_exists($data['moduleSettingsComponent']);
                            if ($exists) {
                                $resourceClass = $data['moduleSettingsComponent'];
                                if (method_exists($resourceClass, 'getUrl')) {
                                    $url = $resourceClass::getUrl();
                                    if ($url) {
                                        return [
                                            \Filament\Forms\Components\View::make('microweber-live-edit::module-settings-iframe')
                                                ->viewData([
                                                    'iframeUrl' => $url,
                                                    'resourceClass' => $resourceClass,
                                                    'data' => $data,
                                                    'params' => $params
                                                ])
                                        ];
                                    }
                                }
                            }
                        }
                        if (!$exists) {
                            return [
                                TextInput::make('error')
                                    ->label('Error')
                                    ->readOnly()
                                    ->default('Livewire or Filament Component not found: '
                                        . $data['moduleSettingsComponent'])
                            ];
                        }
                        $liveEditIframeData = [];
                        if (isset($data['liveEditIframeData']) and !empty($data['liveEditIframeData'])) {
                            $liveEditIframeData = $data['liveEditIframeData'];
                        }
                        return [
                            Livewire::make($data['moduleSettingsComponent'],
                                ['params' => $params, 'liveEditIframeData' => $liveEditIframeData])
                        ];

                    }
                }
            )
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->stickyModalHeader(true)
            //  ->modalWidth(MaxWidth::ExtraLarge)
            ->modalWidth(MaxWidth::Medium)
            ->extraModalWindowAttributes(['class' => 'mw-module-settings-live-edit-modal'])
            ->slideOver();


    }

    public function generateAction($actionName, $contentType)
    {


        if ($contentType == 'category') {
            $formArray = CategoryResource::formArray();
        } else {
            $formArray = ContentResource::formArray([
                'contentType' => $contentType
            ]);
        }


        return Action::make($actionName)
            ->label('Create ' . $contentType)
            ->modalHeading('Create ' . $contentType)
//            ->modalContent(view('microweber-live-edit::modal.generate-action', [
//                'contentType' => $contentType
//            ]))
            ->form($formArray)
            ->action(function ($data) use ($contentType) {

                $data['content_type'] = $contentType;
                //   $data['layout_file'] = 'clean.php';

                $model = new Content();
                $model->fill($data);
                $model->save();

                $contentTypeFriendly = ucfirst($contentType);

                Notification::make()
                    ->success()
                    ->title($contentTypeFriendly . ' is  created')
                    ->body($contentTypeFriendly
                        . ' has been created successfully.')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('viewContent')
                            ->label('View ' . $contentTypeFriendly)
                            ->url(content_link($model->id))
                            ->button(),
                    ])
                    ->send();

            })
            ->modalSubmitActionLabel('Save')
            ->slideOver();
    }
}
