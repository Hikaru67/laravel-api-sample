<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use DB;

class UserRepository extends BaseRepository
{
    /**
     * @return  User
     */
    public function getModel()
    {
        return User::class;
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
            case 'email':
            case 'username':
                return $query->where($column, 'like', '%' . $data . '%');
                break;
            default:
                return $query;
                break;
        }
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function updateToken(User $user)
    {
        $user->api_token = $user->createToken($user->email)->accessToken;
    }

    /**
     * @param User $user
     * @param array $roles
     *
     * @return void
     */
    public function syncRoles(User $user, $roles)
    {
        $user->syncRoles($roles);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function getPermissions(User $user)
    {
        $user->permissions = $user->hasRole(Role::ADMIN) ? Permission::all() : $user->getPermissionsViaRoles();
    }

    /**
     * Get list user menus.
     *
     * @param User $user
     *
     * @return Menu
     */
    public function getMenus(User $user)
    {
        return Menu::with('menus')->where('parent_id', 0)->orderBy('position', 'asc')->get();
    }

    /**
     * Get list user menus by role.
     *
     * @param User $user
     *
     * @return Menu
     */
    public function getMenusByRole(User $user)
    {
        if ($user->hasRole(Role::ADMIN)) {
            return Menu::with('menus')->where('parent_id', 0)->orderBy('position', 'asc')->get();
        }

        $roles = $user->roles->pluck('id')->toArray();

        if (!count($roles)) {
            return [];
        }

        $menuIds = DB::table('model_has_roles')->select('model_id')->whereIn('role_id', $roles)->where('model_type', Menu::class)->get()->pluck('model_id')->toArray();

        if (!count($menuIds)) {
            return [];
        }

        $menus = Menu::whereIn('id', $menuIds)->orderBy('parent_id', 'asc')->orderBy('position', 'asc')->get();

        return $this->recursiveMenu($menus);
    }

    /**
     * Recursive menu.
     *
     * @param array $menus
     * @param int $parentId
     *
     * @return Menu
     */
    public function recursiveMenu($menus = [], $parentId = 0)
    {
        return
            collect($menus)
                ->filter(function ($item) use ($parentId) {
                    return $item->parent_id == $parentId;
                })
                ->map(function ($item) use ($menus) {
                    $item->menus = $this->recursiveMenu($menus, $item->id);

                    return $item;
                })
                ->values();
    }

    /**
     * Get grant client.
     *
     * @return Collection
     */
    public function getGrantClient()
    {
        $condition = [
            'password_client' => 1,
            'personal_access_client' => 0,
            'provider' => $this->model->getTable(),
        ];

        return DB::table('oauth_clients')->where($condition)->first();
    }

    /**
     * Get user by email
     *
     * @param string $email
     */
    public function getUserByEmail($email) {
        return $this->model->where('email', $email)->first();
    }
}
