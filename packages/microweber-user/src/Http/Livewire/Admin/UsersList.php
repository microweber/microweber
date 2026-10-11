<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use Livewire\WithPagination;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Export\Formats\XlsxExport;
use MicroweberPackages\User\Models\User;

class UsersList extends AdminComponent
{
    use WithPagination;

    public $orderBy;
    public $orderDirection;
    public $role;
    public $keyword;
    public $exportResults = false;
    public $pageLimit = 15;

    public $queryString = [
        'role',
        'orderBy',
        'orderDirection',
        'keyword',
    ];

    public $listeners = [
        'refreshUserList' => '$refresh',
    ];

    public function render()
    {

        $usersQuery = User::filter([
            'orderBy' => $this->orderBy,
            'orderDirection' => $this->orderDirection,
            'keyword' => $this->keyword,
        ]);

        if (!empty($this->role)) {
            if ($this->role == 'admin') {
                $usersQuery->where('is_admin', 1);
            }
            if ($this->role == 'user') {
                $usersQuery->where('is_admin', 0);
            }
        }

        if ($this->exportResults) {

            $users = $usersQuery->get();
            $exportedUsers = [];
            foreach ($users as $user) {
                $exportedUser = $user->toArray();
                $exportedUser['created_at'] = $user->created_at->format('Y-m-d H:i:s');
                $exportedUser['updated_at'] = $user->updated_at->format('Y-m-d H:i:s');
                $exportedUsers[] = $exportedUser;
            }

            $exportExcel = new XlsxExport();
            $exportExcel->data['mw_export_users_' . date('Y-m-d-H-i-s')] = $exportedUsers;
            $exportExcel = $exportExcel->start();
            $exportExcelFile = $exportExcel['files']['0']['filepath'];

            return response()->download($exportExcelFile);

        } else {
            $users = $usersQuery
                ->paginate(10);
        }

        $exportUrl = '?exportResults=true';

        return view('admin::livewire.users.list', [
            'exportUrl'=>$exportUrl,
            'users'=>$users
        ]);

    }

}
