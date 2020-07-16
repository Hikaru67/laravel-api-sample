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
     * @param Menu $menu
     * @param array $roles
     *
     * @return void
     */
    public function syncRoles(Menu $menu, $roles)
    {
        $menu->syncRoles($roles);
    }
}
