<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'post_count',
        'trust_percentage',
        'long_desc',
        'created_by',
        'updated_by',
        'status',
    ];
}
