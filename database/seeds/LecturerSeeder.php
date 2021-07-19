<?php

use Illuminate\Database\Seeder;
use App\Models\Lecturer;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class LecturerSeeder extends Seeder
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
            Permission::updateOrCreate(['name' => 'lecturers.' . $key]);
        }

        $students = factory(Lecturer::class, 50)->create();
    }
}
