<?php

namespace MicroweberPackages\Modules\ContactForm\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Form\Models\FormData;
use MicroweberPackages\Form\Models\FormList;

class ListComponent extends AdminComponent
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $listeners = [
        "loadListFormData" => 'refresxxxh',
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

    public function refresxxxh()
    {
        dd(222);
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

    public function preview($id)
    {
        $getFormData = FormData::where('id', $id)->first();
        if ($getFormData) {
            if ($getFormData->is_read != 1) {
                $getFormData->is_read = 1;
                $getFormData->save();
            }
        }

        $this->emit('openModal', 'contact-form.form-data-preview-modal', [
            'formDataId' => $id
        ]);
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
        $params = [];
        $params['id'] = 0;
        if (isset($filter['formListId'])) {
            $params['id'] = $filter['formListId'];
        }

        $data = mw()->forms_manager->export_to_excel($params);
        if (isset($data['download'])) {
            return $this->redirect($data['download']);
        }
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
