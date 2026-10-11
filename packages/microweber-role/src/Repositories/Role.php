<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/6/2020
 * Time: 3:46 PM
 */

namespace MicroweberPackages\Role\Repositories;

class Role
{
    public static function all()
    {
        $roles = [];

      /*  $findAdminRole = \Spatie\Permission\Models\Role::where('name','Admin')->first();
        if (!$findAdminRole) {

            // Create Admin Role
            $permissions = \Spatie\Permission\Models\Permission::all();

            $newRole = new  \Spatie\Permission\Models\Role();
            $newRole['name'] = 'Admin';
            $newRole->save();

            $newRole->givePermissionTo($permissions);
        }*/

        $getRoles = \Spatie\Permission\Models\Role::all();
        if (!empty($getRoles)) {
            foreach ($getRoles as $role) {
                $roles[] = $role;
            }
        }
        $roles[] = [
            'name'=>'User'
        ];
        $roles[] = [
          'name'=>'Super Admin'
        ];

        return $roles;
    }
}