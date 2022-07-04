<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kodeine\Metable\Metable;

class UserMeta extends Model
{
    use HasFactory, SoftDeletes;
    use Metable;

    protected $fillable = [
        'user_id',
        'meta_key',
        'meta_value',
        'status'
    ];
}
