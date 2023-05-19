<?php

namespace MicroweberPackages\Modules\ContactForm\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Form\Models\FormData;
use MicroweberPackages\Form\Models\FormList;
use MicroweberPackages\Modules\TodoModuleLivewire\Models\Todo;

class ListComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $listeners = ["loadList" => '$refresh'];

    public $filter = [
        "keyword" => "",
        "order_field" => "id",
        "order_type" => "desc",
    ];

    public $queryString = [
        'filter',
        'page'
    ];

    public $loadingMessage;

    public $confirmingDeleteId = false;


    public function mount()
    {
        $this->loadingMessage = "Loading forms data...";
    }

    public function render()
    {
        $formsLists = FormList::all();
        $getFormDataQuery = FormData::query();

        // Search
        if (!empty($this->filter['keyword'])) {
            $keyword = $this->filter['keyword'];
            $getFormDataQuery->where(function ($query) use ($keyword) {
                $query->where('id', 'LIKE', $keyword . '%');
            });
        }

        // Ordering
        if (!empty($this->filter["order_field"])) {
            $order_type = (!empty($this->filter["order_type"])) ? $this->filter["order_type"] : 'ASC';
            $getFormDataQuery->orderBy($this->filter["order_field"], $order_type);
        }

        // Paginating;
        $formsData = $getFormDataQuery->paginate(10);

        return view('contact-form::admin.contact-form.list', compact('formsData','formsLists'));
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete($id)
    {
        $formData = FormData::where('id', $id)->first();
        if ($formData == null) {
            return [];
        }

        $formData->delete();
    }
}
