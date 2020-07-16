<?php

namespace App\Repositories;

use App\Models\Role;
use DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Str;

class RoleRepository extends BaseRepository
{
    /**
     * @return Role
     */
    public function getModel()
    {
        return Role::class;
    }

    /**
     * @param mixed $query
     * @param mixed $column
     * @param mixed $data
     *
     * @return Query
     */
    public function search($query, $column, $data)
    {
        switch ($column) {
            case 'name':
                return $query->where($column, 'like', '%'.$data.'%');
                break;
            default:
                return $query;
                break;
        }
    }

    /**
     * @param Role $role
     *
     * @return void
     */
    public function delete($role)
    {
        DB::table('model_has_roles')->where('role_id', $role->id)->delete();
        $role->syncPermissions();
        $role->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * @param Role $role
     * @param array $permissions
     *
     * @return void
     */
    public function syncPermissions(Role $role, $permissions)
    {
        $role->syncPermissions($permissions);
    }

    /**
     * Get permission list.
     *
     * @return Permission
     */
    public function getPermissions()
    {
        return Permission::all();
    }
}
