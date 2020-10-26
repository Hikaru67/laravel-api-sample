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

        $position = 1;

        // user groups
        $userMenu = Menu::updateOrCreate(
            ['title' => 'module.user'],
            ['link' => '/user', 'position' => $position++, 'icon' => 'user-friends',  'parent_id' => 0]
        );

        // role groups
        $roleMenu = Menu::updateOrCreate(
            ['title' => 'module.role'],
            ['link' => '/role', 'position' => $position++, 'icon' => 'balance-scale',  'parent_id' => 0]
        );

        // menu groups
        $menuMenu = Menu::updateOrCreate(
            ['title' => 'module.menu'],
            ['link' => '/menu', 'position' => $position++,  'icon' => 'list',  'parent_id' => 0]
        );
    }
}
