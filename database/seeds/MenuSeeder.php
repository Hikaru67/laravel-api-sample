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
            ['title' => 'user'],
            ['link' => '/user', 'position' => 1, 'icon' => 'user-friends',  'parent_id' => 0]
        );

        // role groups
        $roleMenu = Menu::updateOrCreate(
            ['title' => 'role'],
            ['link' => '/role', 'position' => 2, 'icon' => 'balance-scale',  'parent_id' => 0]
        );

        // menu groups
        $menuMenu = Menu::updateOrCreate(
            ['title' => 'menu'],
            ['link' => '/menu', 'position' => 3,  'icon' => 'list',  'parent_id' => 0]
        );
    }
}
