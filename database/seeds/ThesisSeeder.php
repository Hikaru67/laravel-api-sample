<?php

use Illuminate\Database\Seeder;
use App\Models\Thesis;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class ThesisSeeder extends Seeder
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
            Permission::updateOrCreate(['name' => 'theses.' . $key]);
        }

        factory(Thesis::class, 100)->create();
    }
}
