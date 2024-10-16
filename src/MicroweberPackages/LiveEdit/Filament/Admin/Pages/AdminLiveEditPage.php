<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages;


use App\Filament\Admin\Resources\ContentResource;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\LiveEdit\Filament\Actions\CustomViewAction;
use MicroweberPackages\Media\Models\Media;
use MicroweberPackages\Modules\Logo\Http\Livewire\LogoModuleSettings;
use function Clue\StreamFilter\fun;

class AdminLiveEditPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $description = '';
    protected static ?string $slug = 'live-edit';


    protected static string $view = 'microweber-live-edit::iframe-page';
    protected static string $layout = 'filament-panels::components.layout.live-edit';

    use InteractsWithActions;
    use InteractsWithForms;

    public function render(): View
    {
        $params = request()->all();
        return view($this->getView(), $this->getViewData())
            ->layout($this->getLayout(), [
                'livewire'        => $this,
                'params'          => $params,
                'maxContentWidth' => $this->getMaxContentWidth(),
                ...$this->getLayoutData(),
            ]);
    }


    public function addContentAction(): Action
    {
        $actions = [];
        $actions[] = [
            'title'       => 'New Page',
            'description' => 'Create a new page to your website or online store, choose from pre-pared page designs ',
            'action'      => 'addPageAction',
            'icon'        => 'mw-add-page',
        ];
        $actions[] = [
            'title'       => 'New Post',
            'description' => 'Add new post to your blog page, linked to category of main page on your website ',
            'action'      => 'addPostAction',
            'icon'        => 'mw-add-post',
        ];
        $actions[] = [
            'title'       => 'New Category',
            'description' => 'Add new category and organize your blog posts or items from the shop in the right way ',
            'action'      => 'addPageAction',
            'icon'        => 'mw-add-category',
        ];
        $actions[] = [
            'title'       => 'New Product',
            'description' => 'Add new product to your online store, choose from pre-pared product designs ',
            'action'      => 'addProductAction',
            'icon'        => 'mw-add-product',
        ];

        return Action::make('addContentAction')
            ->form([
                \Filament\Forms\Components\View::make('microweber-live-edit::add-content-modal')
                    ->viewData([
                        'actions' => $actions
                    ])
            ])
            ->modalSubmitAction(null)
            ->modalCancelAction(null)
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
        return $this->generateAction('addPostAction', 'post');
    }

    public function openModuleSettingsAction(): Action
    {
        return Action::make('openModuleAction')
            ->label('Module Settings')
            ->form(
                function (array $arguments) {
                    $data = $arguments['data'];
                    $params = $data['params'];
                    if (isset($data['moduleSettingsComponent'])) {
                        if (!livewire_component_exists($data['moduleSettingsComponent'])) {
                            return [
                                TextInput::make('error')
                                    ->label('Error')
                                    ->readOnly()
                                    ->default('Component not found: '
                                        .$data['moduleSettingsComponent'])
                            ];
                        }

                        return [
                            Livewire::make($data['moduleSettingsComponent'],
                                ['params' => $params])
                        ];
                    }
                }
            )
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->stickyModalHeader(true)
            ->modalWidth(MaxWidth::ExtraLarge)
            ->extraModalWindowAttributes(['class' => 'mw-module-settings-live-edit-modal'])
            ->slideOver();


    }

    public function generateAction($actionName, $contentType)
    {
        return Action::make($actionName)
            ->label('Create '.$contentType)
            ->modalHeading('Create '.$contentType)
//            ->modalContent(view('microweber-live-edit::modal.generate-action', [
//                'contentType' => $contentType
//            ]))
            ->form(ContentResource::formArray([
                'contentType' => $contentType
            ]))
            ->action(function ($data) use ($contentType) {

                $data['content_type'] = $contentType;
                $data['layout_file'] = 'clean.php';

                $model = new Content();
                $model->fill($data);
                $model->save();

                $contentTypeFriendly = ucfirst($contentType);

                Notification::make()
                    ->success()
                    ->title($contentTypeFriendly.' is  created')
                    ->body($contentTypeFriendly
                        .' has been created successfully.')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('viewContent')
                            ->label('View '.$contentTypeFriendly)
                            ->url(content_link($model->id))
                            ->button(),
                    ])
                    ->send();

            })
            ->modalSubmitActionLabel('Create '.$contentType)
            ->slideOver();
    }
}
