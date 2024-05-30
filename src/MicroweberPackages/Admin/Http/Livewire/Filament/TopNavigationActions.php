<?php

namespace MicroweberPackages\Admin\Http\Livewire\Filament;


use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Livewire\Component;

class TopNavigationActions extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function render(): View
    {
        $links = [];
        $links[] = [
            'title' => 'New Page',
            'description' => 'Create a new page to your website or online store, choose from pre-pared page designs ',
            'url' => '',
            'icon' => 'mw-dashboard',
        ];
        $links[] = [
            'title' => 'New Post',
            'description' => 'Add new post to your blog page, linked to category of main page on your website ',
            'url' => '',
            'icon' => 'mw-dashboard',
        ];
        $links[] = [
            'title' => 'New Category',
            'description' => 'Add new category and organize your blog posts or items from the shop in the right way ',
            'url' => '',
            'icon' => 'mw-dashboard',
        ];
        $links[] = [
            'title' => 'New Product',
            'description' => 'Add new product to your online store, choose from pre-pared product designs ',
            'url' => '',
            'icon' => 'mw-dashboard',
        ];

        return view('admin::livewire.filament.top-navigation-actions', [
            'links'=> $links,
        ]);
    }
}
