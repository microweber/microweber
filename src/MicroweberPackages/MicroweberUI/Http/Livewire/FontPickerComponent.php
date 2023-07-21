<?php
namespace MicroweberPackages\MicroweberUI\Http\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class FontPickerComponent extends AdminComponent
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $fonts = get_editor_fonts();

        $fonts = $this->paginate($fonts, 10);

        $this->dispatchBrowserEvent('font-picker-load-fonts',[
            'fonts' => $fonts->items()
        ]);

        return view('microweber-ui::livewire.font-picker', [
            'fonts' => $fonts
        ]);
    }

    public function paginate($items, $perPage = 5, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        $currentPage = $page;
        $offset = ($currentPage * $perPage) - $perPage ;
        $itemsToShow = array_slice($items , $offset , $perPage);

        return new LengthAwarePaginator($itemsToShow ,$total   ,$perPage);
    }

}
