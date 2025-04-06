<?php

namespace Modules\Billing\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\User\Models\User;


class Users extends Component
{
    use WithPagination;

    public $listeners = [
        'userSubscriptionUpdated' => '$refresh',
    ];
    public $keyword;

    protected $queryString = ['keyword'];

    public function render()
    {
        $usersQuery = User::query();

        if (!empty($this->keyword)) {
            $usersQuery->where('first_name', 'like', '%' . $this->keyword . '%');
            $usersQuery->orWhere('last_name', 'like', '%' . $this->keyword . '%');
            $usersQuery->orWhere('email', 'like', '%' . $this->keyword . '%');
        }

        $users = $usersQuery->paginate(10);

        return view('billing::admin.livewire.users', [
            'users' => $users
        ]);
    }
}
