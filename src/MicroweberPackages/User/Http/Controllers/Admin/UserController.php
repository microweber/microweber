<?php

namespace MicroweberPackages\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
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

            $exportExcel = new XlsxExport();
            $exportExcel->data['mw_export_users_' . date('Y-m-d-H-i-s')] = $users->toArray();
            $exportExcel = $exportExcel->start();
            $exportExcelFile = $exportExcel['files']['0']['filepath'];

            return response()->download($exportExcelFile);

        } else {
            $users = $usersQuery
                ->paginate($request->get('limit', $this->pageLimit))
                ->appends($request->except('page'));
        }

        $exportUrl = $request->fullUrlWithQuery(['exportResults'=>true]);

        return $this->view('user::admin.users.index', [
            'isAdmin'=>$isAdmin,
            'keyword'=>$keyword,
            'orderBy'=>$orderBy,
            'exportUrl'=>$exportUrl,
            'orderDirection'=>$orderDirection,
            'users'=>$users
        ]);
    }

    public function show($id)
    {
        $user = User::where('id',$id)->first();

        if ($user == false) {
            return redirect(route('admin.user.index'));
        }

        return $this->view('user::admin.users.show', [
            'user'=>$user
        ]);
    }
}
