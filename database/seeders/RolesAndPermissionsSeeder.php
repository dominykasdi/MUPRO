<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Leidimai
        $permissions = [
            'create course',
            'edit course',
            'delete course',
            'enroll course',
            'complete step',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Rolių ir leidimų priskyrimas
        $curatorRole = Role::create(['name' => 'curator']);
        $curatorRole->givePermissionTo(['create course', 'edit course', 'delete course']);

        $learnerRole = Role::create(['name' => 'learner']);
        $learnerRole->givePermissionTo(['enroll course', 'complete step']);

        // Kuratorius 1
        $curator = User::create([
            'role_name' => 'curator',
            'name' => 'Dominykas',
            'email' => 'dominykas@mupro.lt',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $curator->assignRole($curatorRole);

        // Kuratorius 2
        $curator = User::create([
            'role_name' => 'curator',
            'name' => 'Kuratorius',
            'email' => 'kuratorius@mupro.lt',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $curator->assignRole($curatorRole);

        // Mokinys 1
        $learner = User::create([
            'role_name' => 'learner',
            'name' => 'Mokinys',
            'email' => 'mokinys@mupro.lt',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $learner->assignRole($learnerRole);

        
    }
}
