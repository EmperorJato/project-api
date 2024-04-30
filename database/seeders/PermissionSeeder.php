<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                "name" => "View Project Dashboard",
                "slug" => "view-project-dashboard"
            ],
            [
                "name" => "View All Project",
                "slug" => "view-all-project"
            ],
            [
                "name" => "View Project",
                "slug" => "view-project"
            ],
            [
                "name" => "Create Project",
                "slug" => "create-project"
            ],
            [
                "name" => "Update Project",
                "slug" => "update-project"
            ],
            [
                "name" => "Delete Project",
                "slug" => "delete-project"
            ],
        ]);
    }
}
