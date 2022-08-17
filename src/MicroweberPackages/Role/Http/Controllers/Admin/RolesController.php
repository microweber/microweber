<?php

namespace MicroweberPackages\Role\Http\Controllers\Admin;

use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Role\Repositories\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;


class RolesController extends AdminController
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::with('users')->get();

        return $this->view('role::admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $selectedPermissions = array();
        $permissionGroups = Permission::all();

        return $this->view('role::admin.roles.edit', compact('permissionGroups', 'selectedPermissions'));
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \App\Http\Requests\StoreRolesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:20',
            'permission' => 'required',
        ]);

        $role = Role::create($request->except('permission'));
        $permissions = $request->input('permission') ? $request->input('permission') : [];
        $role->givePermissionTo($permissions);

        return redirect()->route('roles.index');
    }


    /**
     * Show the form for editing Role.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissions = Permission::all();
        $role = Role::findOrFail($id);
        $permissionGroups = Permission::all();

        $selectedPermissions = $role->permissions()->pluck('name')->toArray();

        /*  JavaScript::put([
              'foo' => $selectedPermissions
          ]);*/

        return $this->view('role::admin.roles.edit', compact('role', 'permissions', 'selectedPermissions', 'permissionGroups'));
    }

    /**
     * Update Role in storage.
     *
     * @param  \App\Http\Requests\UpdateRolesRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // TODO

        $request->validate([
            'name' => 'required|max:20',//unique:roles
            'permission' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->except('permission'));
        $permissions = $request->input('permission') ? $request->input('permission') : [];
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index');
    }

    public function cloneRole(Request $request)
    {
        $oldRole = Role::with('permissions')->find($request->id);

        $role = Role::create([
            'name' => $oldRole->name . ' (dublicate)',
        ]);
        $role->givePermissionTo($oldRole->permissions);

        return redirect(route('roles.edit', $role->id))->with('status', 'Role is cloned.');
    }

    /**
     * Remove Role from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index');
    }

    /**
     * Delete all selected Role at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Role::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
