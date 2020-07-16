<?php

namespace MicroweberPackages\Roles\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tanwencn\Admin\Facades\Admin;
use Illuminate\Support\Facades\Auth;
use Tanwencn\Admin\Http\Rules\HasAlreadyPermission;

class PermissionController extends Controller
{
    use ValidatesRequests, Package;

    public function __construct()
    {
        $this->guards = array_keys(config('auth.guards', []));
    }

    public function index(Request $request)
    {
        $results = app(PermissionRegistrar::class)->getPermissions();
        //$results = Permission::all();
        /*$search = $request->query('search');
        if (!empty($search)) {
            $model->where(function ($query) use ($search) {
                $search = ['like', "%{$search}%"];
                return $query->where('name', ...$search)->orWhere('name', ...$search);
            });
        }

        $results = $model->paginate();*/

        return $this->view('index', compact('results'));
    }

    public function create()
    {
        return $this->_form(new Permission());
    }

    protected function _form(Permission $model)
    {
        $this->hasAlreadyPermission($model);

        $guards = $this->guards;

        return $this->view('add_edit', compact('model', 'guards'));
    }

    public function edit($id)
    {
        $model = Permission::findOrFail($id);

        return $this->_form($model);
    }

    public function store()
    {
        return $this->save(new Permission());
    }

    public function update($id)
    {
        $permission = Permission::findOrfail($id);

        return $this->save($permission);
    }

    protected function save(Permission $model)
    {
        $request = request();

        $this->validate($request, [
            'guard' => ['required', Rule::in($this->guards)],
            'name' => ['required', 'max:255',
                Rule::unique('permissions')->ignore($model)->where(function ($query)use($request) {
                    return $query->where('guard_name', $request->input('guard'));
                })
            ]
        ]);

        $this->hasAlreadyPermission($model);

        $model->name = $request->input('name');

        $model->guard_name = $request->input('guard');

        $model->save();

        return redirect(Admin::action('index'))->with('success', trans('admin.save_succeeded'));
    }

    public function destroy($id, Request $request)
    {
        $ids = $id ? [$id] : $request->input('ids');
        foreach ($ids as $id) {
            $model = Permission::query()->findOrFail($id);
            $this->hasAlreadyPermission($model);
            $model->delete();
        }

        return response([
            'message' => trans('admin.delete_succeeded'),
        ]);
    }

    protected function hasAlreadyPermission($model)
    {
        //if($model->id) abort_unless(Auth::user()->hasPermissionTo(intval($model->id), $model->guard_name) || Auth::user()->hasRole('superadmin'), 402, "you is no permission id {$model->name}");
    }

    protected function abilitiesMap()
    {
        return [
            'index' => 'view_permission',
            'edit' => 'edit_permission',
            'update' => 'edit_permission',
            'create' => 'add_permission',
            'store' => 'add_permission',
            'destroy' => 'delete_permission'
        ];
    }


}
