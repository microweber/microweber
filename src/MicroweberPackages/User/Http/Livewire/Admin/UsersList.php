<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Export\Formats\XlsxExport;
use MicroweberPackages\User\Models\User;

class UsersList extends Component
{
    use WithPagination;

    public $orderBy;
    public $orderDirection;
    public $keyword;
    public $exportResults = false;
    public $pageLimit = 15;

    public $queryString = [
        'orderBy',
        'orderDirection',
        'keyword',
    ];

    public $listeners = [
        'refreshUserList' => '$refresh',
    ];

    public function render()
    {
        $filterFields = [];

        if (!isset($filterFields['orderBy'])) {
            $filterFields['orderBy'] = 'created_at';
            $filterFields['orderDirection'] = 'desc';
        }

        $usersQuery = User::filter([
            'orderBy' => $this->orderBy,
            'orderDirection' => $this->orderDirection,
            'keyword' => $this->keyword,
        ]);

        if ($this->exportResults) {

            $users = $usersQuery->get();

            $exportExcel = new XlsxExport();
            $exportExcel->data['mw_export_users_' . date('Y-m-d-H-i-s')] = $users->toArray();
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
