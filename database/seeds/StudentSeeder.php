<?php

use Illuminate\Database\Seeder;
use App\Models\Student;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class StudentSeeder extends Seeder
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
            Permission::updateOrCreate(['name' => 'students.' . $key]);
        }

        factory(Student::class, 100)->create();
    }
}
