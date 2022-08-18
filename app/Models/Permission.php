<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'permission_category_id', 'slug', 'route_name'
    ];

    public function permission_category()
    {
        return $this->belongsTo(PermissionCategory::class);
    }

}
