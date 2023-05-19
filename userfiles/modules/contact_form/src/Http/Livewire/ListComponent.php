<?php

namespace MicroweberPackages\Modules\ContactForm\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Modules\TodoModuleLivewire\Models\Todo;

class ListComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $listeners = ["loadList" => '$refresh'];

    public $filter = [
        "search" => "",
        "status" => "",
        "order_field" => "",
        "order_type" => "",
    ];

    public $loadingMessage;

    public $confirmingDeleteId = false;


    public function mount()
    {
        $this->loadingMessage = "Loading Todos...";
    }

    public function render()
    {

        $query = [];

        if (!empty($this->filter["status"])) {
            $query["status"] = $this->filter["status"];
        }

        $getTodoQuery = Todo::where($query);

        // Search
        if (!empty($this->filter["search"])) {
            $filter = $this->filter;
            $getTodoQuery = $getTodoQuery->where(function ($query) use ($filter) {
                $query->where('title', 'LIKE', $this->filter['search'] . '%');
            });
        }

        // Ordering
        if (!empty($this->filter["order_field"])) {
            $order_type = (!empty($this->filter["order_type"])) ? $this->filter["order_type"] : 'ASC';
            $getTodoQuery = $getTodoQuery->orderBy($this->filter["order_field"], $order_type);
        }

        // Paginating;
        $todos = $getTodoQuery->paginate();

        return view('contact-form::admin.contact-form.list', compact('todos'));
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete($id)
    {
        $todo = Todo::where('id', $id)->first();
        if ($todo == null) {
            return [];
        }

        $todo->delete();

        $this->emitTo('todo-module-livewire.notification-component', 'flashMessage', 'error', 'Task deleted successfully.');
        $this->emitTo('todo-module-livewire.list-component', 'loadList');
    }
}
