<?php

namespace MicroweberPackages\Roles\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ValidatesRequests,Package;

    protected $guards;

    public function __construct()
    {
        $guards = array_keys(config('auth.guards', []));
        $this->guards = array_combine($guards, $guards);
    }

    public function index(Request $request)
    {
        $model = Role::query();
        $search = $request->query('search');
        if (!empty($search)) {
            $model->where(function ($query) use ($search) {
                $search = ['like', "%{$search}%"];
                return $query->where('name', ...$search)->orWhere('name', ...$search);
            });
        }

        /*if(!Auth::user()->hasRole('superAdmin'))
            $model->where('guard_name', Auth::user()->guard_name);*/

        $results = $model->paginate();

        return $this->view('index', compact('results'));
    }

    public function create()
    {
        return $this->_form(new Role());
    }

    protected function _form(Role $model)
    {
        $permissions_group = Permission::where('name', '!=', 'dashboard')->where('guard_name', 'admin')->get()->groupBy('guard_name');

        $current_permissions = old('permissions', $model->permissions->pluck('id')->toArray());

        $guards = $this->guards;

        return $this->view('add_edit', compact('model', 'permissions_group', 'current_permissions', 'guards'));
    }

    public function edit($id)
    {
        $model = Role::findOrFail($id);

        return $this->_form($model);
    }

    public function store()
    {
        return $this->save(new Role());
    }

    public function update($id)
    {
        $role = Role::findOrfail($id);
        if ($role->name == 'superadmin' && request()->input('name') != $role->name) {
            abort(422);
        }
        return $this->save($role);
    }

    protected function save(Role $model)
    {
        $request = request();

        $this->validate($request, [
            'guard' => ['required', Rule::in($this->guards)],
            'name' => ['required', 'max:255',
                Rule::unique('roles')->ignore($model)->where(function ($query)use($request) {
                    return $query->where('guard_name', $request->input('guard'));
                })
            ],
            'permissions' => 'array'
        ]);

        /*if($request->filled('permissions')) {
            foreach ($request->input('permissions') as $val) {
                abort_unless(Auth::user()->hasRole('superadmin') || Auth::user()->hasPermissionTo(intval($val)), 402, "you is no permission id {$val}");
            }
        }*/

        $model->name = $request->input('name');

        $model->guard_name = $request->input('guard');

        $model->save();

        if ($request->filled('permissions')) {
            $model->syncPermissions(array_merge($request->input('permissions', []), ['dashboard']));
        }

        return redirect(\Admin::action('index'))->with('success', trans('admin.save_succeeded'));
    }

    public function destroy($id, Request $request)
    {
        $ids = $id ? [$id] : $request->input('ids');
        foreach ($ids as $id) {
            $model = Role::findOrFail($id);
            if ($model->name == 'superadmin') continue;
            $model->delete();
        }

        return response([
            'message' => trans('admin.delete_succeeded')
        ]);
    }

    protected function abilitiesMap()
    {
        return [
            'index' => 'view_role',
            'edit' => 'edit_role',
            'update' => 'edit_role',
            'create' => 'add_role',
            'store' => 'add_role',
            'destroy' => 'delete_role'
        ];
    }


}
