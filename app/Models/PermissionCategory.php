<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PermissionCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id', 'name'
    ];

    public function permission()
    {
        return $this->hasMany(Permission::class);
    }

}
