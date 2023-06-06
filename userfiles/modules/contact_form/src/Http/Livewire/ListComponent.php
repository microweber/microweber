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

    public $listeners = [
        "loadListFormData" => '$refresh',
    ];

    public $filter = [
        "keyword" => "",
        "orderField" => "id",
        "orderType" => "desc",
        "formListId" => "",
    ];

    public $queryString = [
        'filter',
        'itemsPerPage',
        'page'
    ];

    public $itemsPerPage = 10;

    public $loadingMessage;
    public $confirmingDeleteId = false;


    public function mount()
    {
        $this->loadingMessage = "Loading forms data...";
    }

    public function updatedFilter()
    {
        $this->gotoPage(1);
    }

    public function updatedItemsPerPage()
    {
        $this->gotoPage(1);
    }

    public function render()
    {
        $formsLists = FormList::all();
        $getFormDataQuery = FormData::query();

        // Search
        if (!empty($this->filter['keyword'])) {
            $keyword = $this->filter['keyword'];
            $keyword = trim($keyword);
            $getFormDataQuery->whereHas('formDataValues', function ($query) use ($keyword) {
                $query->where('field_value', 'LIKE', '%'.$keyword . '%');
            });
        }

        if (!empty($this->filter['formListId'])) {
            $formListId = (int) $this->filter['formListId'];
            $getFormDataQuery->where('list_id', $formListId);
        }

        // Ordering
        if (!empty($this->filter["orderField"])) {
            $order_type = (!empty($this->filter["orderType"])) ? $this->filter["orderType"] : 'ASC';
            $getFormDataQuery->orderBy($this->filter["orderField"], $order_type);
        }

        // Paginating;
        $formsData = $getFormDataQuery->paginate($this->itemsPerPage);

        return view('contact-form::admin.contact-form.list', compact('formsData','formsLists'));
    }

    public function markAsRead($id)
    {
        $formData = FormData::where('id', $id)->first();
        if ($formData == null) {
            return [];
        }

        $formData->is_read = 1;
        $formData->save();
    }

    public function markAsUnread($id)
    {
        $formData = FormData::where('id', $id)->first();
        if ($formData == null) {
            return [];
        }
        $formData->is_read = 0;
        $formData->save();
    }

    public function exportDataExcel()
    {

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
