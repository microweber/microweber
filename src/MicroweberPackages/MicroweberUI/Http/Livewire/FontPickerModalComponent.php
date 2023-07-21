<?php
namespace MicroweberPackages\MicroweberUI\Http\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;

class FontPickerModalComponent extends ModalComponent
{
    use WithPagination;

    public $search = '';
    public $category = 'all'; 
    public $categories = [
        'all' => 'All',
        'favorites' => 'Favorites',
        'cyrillic' => 'Cyrillic',
        'latin' => 'Latin',
        'sans-serif' => 'Sans Serif',
        'handwriting' => 'Handwriting',
        'display' => 'Display',
    ];

    public function render()
    {
        $fonts = get_editor_fonts();
        $filteredFonts = [];

        $filterCategory = '';
        if ($this->category !== 'all') {
            $filterCategory = $this->category;
        }

        if (!empty($this->search) || !empty($filterCategory)) {
            foreach ($fonts as $font) {
                $fontFamilyLower = mb_strtolower($font['family']);
                $searchLower = mb_strtolower($this->search);
                if (!empty($this->search)) {
                    if (strpos($fontFamilyLower, $searchLower) !== false) {
                        $filteredFonts[] = $font;
                    }
                }
                if (!empty($filterCategory)) {
                   if (isset($font['category']) && $font['category'] == $filterCategory) {
                       $filteredFonts[] = $font;
                   }
                   if (isset($font['subsets'])
                       && !empty($font['subsets'])
                       && is_array($font['subsets'])
                       && in_array($filterCategory, $font['subsets'])) {
                       $filteredFonts[] = $font;
                   }
                }
            }
        } else {
            $filteredFonts = $fonts;
        }

        $fonts = $this->paginate($filteredFonts, 10);

        $this->dispatchBrowserEvent('font-picker-load-fonts',[
            'fonts' => $fonts->items()
        ]);

        return view('microweber-ui::livewire.modals.font-picker-modal', [
            'fonts' => $fonts
        ]);
    }

    public function category($category) {
        $this->category = $category;
        $this->gotoPage(1);
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
