<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('permissions')) {
            $permissions = Permission::all();

            foreach ($permissions as $permission) {
                Gate::define($permission->slug, function (User $user) use ($permission) {
                    return $user->hasPermissionTo($permission->slug);
                });
            }
        }
    }
}
