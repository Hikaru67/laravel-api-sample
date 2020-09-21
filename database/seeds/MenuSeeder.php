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

        // user groups
        $userMenu = Menu::updateOrCreate(
            ['title' => 'user.index'],
            ['link' => '/user', 'position' => 1, 'icon' => 'user-friends',  'parent_id' => 0]
        );

        // role groups
        $roleMenu = Menu::updateOrCreate(
            ['title' => 'role.index'],
            ['link' => '/role', 'position' => 1, 'icon' => 'balance-scale',  'parent_id' => 0]
        );

        // menu groups
        $menuMenu = Menu::updateOrCreate(
            ['title' => 'menu.index'],
            ['link' => 'menu', 'position' => 1,  'icon' => 'list',  'parent_id' => 0]
        );
    }
}
