<?php

namespace App\Repositories;

use App\Models\Menu;

class MenuRepository extends BaseRepository
{
    /**
     * @return  Menu
     */
    public function getModel()
    {
        return Menu::class;
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
            case 'ids':
                return $query->whereIn('id', $data);
                break;
            default:
                return $query;
                break;
        }
    }

    /**
     * @param Menu $menu
     * @param array $roles
     *
     * @return void
     */
    public function syncRoles(Menu $menu, $roles)
    {
        $menu->syncRoles($roles);
    }

    /**
     * @param array $menus
     * @param array $roles
     *
     * @return void
     */
    public function syncRolesDeep($menus, $roles)
    {
        if ($menus->count()) {
            foreach ($menus as $menu) {
                $menu->syncRoles($roles);
                $this->syncRolesDeep($menu->menus, $roles);
            }
        }
    }
}
