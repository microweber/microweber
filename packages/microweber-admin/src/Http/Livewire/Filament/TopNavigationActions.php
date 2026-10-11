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
        // task-2026-05-04-novice — same plain-English copy as the
        // live-edit +ADD picker (AdminLiveEditPage::addContentAction),
        // kept in sync so the top-nav and live-edit Add menus speak
        // the same human language.
        $links = [];
        $links[] = [
            'title' => 'New Page',
            'description' => 'A standalone page like About, Services or Contact.',
            'url' => admin_url('pages/create'),
            'icon' => 'mw-add-page',
        ];
        $links[] = [
            'title' => 'New Post',
            'description' => 'A blog article or news story that appears in your Blog list.',
            'url' => admin_url('posts/create'),
            'icon' => 'mw-add-post',
        ];
        $links[] = [
            'title' => 'New Category',
            'description' => 'A folder to group your blog posts or shop items.',
            'url' => admin_url('categories/create'),
            'icon' => 'mw-add-category',];
        $links[] = [
            'title' => 'New Product',
            'description' => 'An item to sell in your online shop.',
            'url' =>admin_url('products/create'),
            'icon' => 'mw-add-product',
        ];

        return view('admin::livewire.filament.top-navigation-actions', [
            'links' => $links,
        ]);
    }
}
