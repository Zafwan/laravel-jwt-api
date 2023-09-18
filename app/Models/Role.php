<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the role_permission.
     */
    public function role_permission()
    {
        return $this->hasOne(RolePermission::class, 'role_id');
    }

    /**
     * Set up cascade delete.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($role) {
            $role->role_permission()->delete();
        });
    }
}