<?php

namespace MicroweberPackages\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Export\Formats\XlsxExport;
use MicroweberPackages\User\Models\User;

class UserController extends AdminController
{
    public $pageLimit = 15;

    public function index(Request $request)
    {
        $orderBy = $request->get('orderBy', 'id');
        $orderDirection = $request->get('orderDirection', 'desc');
        $exportResults = $request->get('exportResults', false);
        $isAdmin = $request->get('isAdmin', false);

        $keyword = $request->get('keyword', '');
        if (!empty($keyword)) {
            $keyword = trim($keyword);
        }

        $filterFields = $request->all();

        if (!isset($filterFields['orderBy'])) {
            $filterFields['orderBy'] = 'created_at';
            $filterFields['orderDirection'] = 'desc';
        }

        $usersQuery = User::filter($filterFields);

        if ($exportResults) {

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
                ->paginate($request->get('limit', $this->pageLimit))
                ->appends($request->except('page'));
        }

        $exportUrl = $request->fullUrlWithQuery(['exportResults'=>true]);

        return view('user::admin.users.index', [
            'isAdmin'=>$isAdmin,
            'keyword'=>$keyword,
            'orderBy'=>$orderBy,
            'exportUrl'=>$exportUrl,
            'orderDirection'=>$orderDirection,
            'users'=>$users
        ]);
    }

    public function profile() {

        return view('user::admin.users.show');

    }

    public function edit($id)
    {
        $user = User::where('id',$id)->first();

        if ($user == false) {
            return redirect(route('admin.users.index'));
        }

        return view('user::admin.users.edit', [
            'user'=>$user
        ]);
    }

    public function create()
    {
        return view('user::admin.users.create');
    }

    public function show($id)
    {
        $user = User::where('id',$id)->first();

        if ($user == false) {
            return redirect(route('admin.users.index'));
        }

        return view('user::admin.users.show', [
            'user'=>$user
        ]);
    }
}
