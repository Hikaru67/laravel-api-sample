<?php

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $actions = ['index', 'store', 'show', 'update', 'destroy'];

        foreach ($actions as $key) {
            Permission::updateOrCreate(['name' => 'menu.' . $key]);
        }

        // module list
        $moduleMenu = Menu::updateOrCreate(
            ['title' => 'module', 'parent_id' => 0],
            ['position' => 1]
        );

        $systemMenu = Menu::updateOrCreate(
            ['title' => 'system', 'parent_id' => 0],
            ['position' => 1]
        );

        // user groups
        $userGroup = Menu::updateOrCreate(
            ['title' => 'user', 'parent_id' => $systemMenu->id],
            ['position' => 1]
        );

        $userMenu = Menu::updateOrCreate(
            ['title' => 'user.index', 'parent_id' => $userGroup->id],
            ['link' => '/user', 'position' => 1, 'icon' => 'user-friends']
        );

        // role groups
        $roleGroup = Menu::updateOrCreate(
            ['title' => 'role', 'parent_id' => $systemMenu->id],
            ['position' => 2]
        );

        $roleMenu = Menu::updateOrCreate(
            ['title' => 'role.index', 'parent_id' => $roleGroup->id],
            ['link' => '/role', 'position' => 1, 'icon' => 'balance-scale']
        );

        // menu groups
        $menuGroup = Menu::updateOrCreate(
            ['title' => 'menu', 'parent_id' => $systemMenu->id],
            ['position' => 3]
        );

        $menuMenu = Menu::updateOrCreate(
            ['title' => 'menu.index', 'parent_id' => $menuGroup->id],
            ['link' => 'menus', 'position' => 1]
        );
    }
}
