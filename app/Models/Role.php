<?php

namespace App\Models;

use App\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory, HasPermissions;

    public function hasPermissionTo(...$permissions)
    {
        return $this->permissions()->whereIn('slug', $permissions)->count();
    }

    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    //Default Roles
    public function scopeSuperAdmin($query){
        return $query->where('slug', 'super-admin');
    }

    public function scopeAdmin($query){
        return $query->where('slug', 'admin');
    }

    public function scopeUser($query){
        return $query->where('slug', 'user');
    }
}
