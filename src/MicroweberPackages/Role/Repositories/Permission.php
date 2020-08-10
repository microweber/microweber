<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/6/2020
 * Time: 3:46 PM
 */

namespace MicroweberPackages\Role\Repositories;


class Permission
{
    /**
     * Get the permissions from all the enabled modules.
     *
     * @return array
     */
    public static function all()
    {
        //return static::getEnabledModulePermissions() + static::getActiveThemePermissions();
        return static::getEnabledModulePermissions();
    }

    /*
     *         $adminPermissions =  [
            'admin.users' => [
                'index' => 'user::permissions.users.index',
                'create' => 'user::permissions.users.create',
                'edit' => 'user::permissions.users.edit',
                'destroy' => 'user::permissions.users.destroy',
            ],
            'admin.roles' => [
                'index' => 'user::permissions.roles.index',
                'create' => 'user::permissions.roles.create',
                'edit' => 'user::permissions.roles.edit',
                'destroy' => 'user::permissions.roles.destroy',
            ],
        ];
     */

    public static function getAllPermissionsSlugs()
    {
        $permissionSlugs = [];
        $modules = get_modules();

        foreach ($modules as $module) {

           $modulePermissionSlugs = self::generateModulePermissionsSlugs($module);
           foreach ($modulePermissionSlugs as $modulePermissionSlug) {
               $permissionSlugs[] = $modulePermissionSlug;
           }

        }

        return $permissionSlugs;
    }

    public static function generateModulePermissionsSlugs($module)
    {
        if (!isset($module['module']) || empty($module['module'])) {
            throw new \Exception('Please, set module path.');
        }

        $permissionSlug = $module['module'];
        $permissionSlug = strtolower($permissionSlug);
        $permissionSlug = str_replace(' ', '_', $permissionSlug);
        $permissionSlug = str_replace('/', '.', $permissionSlug);

        $permissionSlugs = [];
        $permissionSlugs['index'] = 'module.'.strtolower($permissionSlug).'.index';
        $permissionSlugs['create'] = 'module.'.strtolower($permissionSlug).'.create';
        $permissionSlugs['edit'] = 'module.'.strtolower($permissionSlug).'.edit';
        $permissionSlugs['destroy'] = 'module.'.strtolower($permissionSlug).'.destroy';

        return $permissionSlugs;
    }

    /**
     * Get enabled module permissions.
     *
     * @return array
     */
    private static function getEnabledModulePermissions()
    {
        $groups = [];
        $modules = get_modules();

        foreach ($modules as $module) {
            if (!isset($module['categories']) || empty($module['categories'])) {
                $module['categories'] = 'admin';
            }

            $module['permission_slugs'] = self::generateModulePermissionsSlugs($module);
            foreach ($module['permission_slugs'] as $permissionSlug) {
                $findPermission = \Spatie\Permission\Models\Permission::where('name', $permissionSlug)->first();
                if (!$findPermission) {
                    \Spatie\Permission\Models\Permission::create([
                        'name'=>$permissionSlug
                    ]);
                }
            }

            $groups[$module['categories']][] = $module;
        }

        return $groups;
    }
}