<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                "name" => "Super Admin",
                "slug" => "super-admin"
            ],
            [
                "name" => "Admin",
                "slug" => "admin"
            ],
            [
                "name" => "User",
                "slug" => "user"
            ],
        ]);

        $adminRole = Role::admin()->firstOrFail();
        $adminPermissions = Permission::whereIn('slug', [
            'view-project-dashboard',
            'view-all-project',
            'view-project',
            'create-project',
            'update-project',
            'delete-project',
        ])->get()->pluck('id')->toArray();

        $adminRole->permissions()->sync($adminPermissions);

        $userRole = Role::user()->firstOrFail();
        $userPermissions = Permission::whereIn('slug', [
            'view-project-dashboard',
            'view-all-project',
            'view-project',
            'create-project',
        ])->get()->pluck('id')->toArray();

        $userRole->permissions()->sync($userPermissions);
    }
}
