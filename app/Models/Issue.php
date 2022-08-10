<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'politician_id', 'content', 'gif', 'status', 'images', 'videos', 'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function politician()
    {
        return $this->belongsTo(Politician::class);
    }
}
