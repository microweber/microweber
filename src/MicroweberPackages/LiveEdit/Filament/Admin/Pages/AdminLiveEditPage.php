<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages;


use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use MicroweberPackages\Media\Models\Media;

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
                'livewire' => $this,
                'params' => $params,
                'maxContentWidth' => $this->getMaxContentWidth(),
                ...$this->getLayoutData(),
            ]);
    }



    public function addContentAction(): Action
    {
        $links = [];
        $links[] = [
            'title' => 'New Page',
            'description' => 'Create a new page to your website or online store, choose from pre-pared page designs ',
            'url' => admin_url('pages/create'),
            'icon' => 'mw-add-page',
        ];
        $links[] = [
            'title' => 'New Post',
            'description' => 'Add new post to your blog page, linked to category of main page on your website ',
            'url' => admin_url('posts/create'),
            'icon' => 'mw-add-post',
        ];
        $links[] = [
            'title' => 'New Category',
            'description' => 'Add new category and organize your blog posts or items from the shop in the right way ',
            'url' => admin_url('categories/create'),
            'icon' => 'mw-add-category',];
        $links[] = [
            'title' => 'New Product',
            'description' => 'Add new product to your online store, choose from pre-pared product designs ',
            'url' =>admin_url('products/create'),
            'icon' => 'mw-add-product',
        ];

        return Action::make('addContentAction')
            ->form([
                \Filament\Forms\Components\View::make('microweber-live-edit::add-content-modal')
                ->viewData([
                    'links' => $links
                ])
            ])
            ->slideOver();
    }

}
