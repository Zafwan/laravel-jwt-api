<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'permission_id' => 'array',
    ];

    /**
     * Get the role that owns the role_permission.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}