<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTrust extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'responded_id', 'trust', 'responded_date', 'status'
    ];
}
