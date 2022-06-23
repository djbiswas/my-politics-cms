<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'politician_id', 'content', 'gif', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function politican()
    {
        return $this->belongsTo(Politician::class);
    }

    public function postImages()
    {
        return $this->hasMany(PostImage::class);
    }

    public function postVideos()
    {
        return $this->hasMany(PostVideo::class);
    }
    
    /**
     *
     * @param type $query
     * @return type Illuminate\Support\Collection
     */
    public function scopeOnlyActive($query)
    {
        return $query->where('status', config('constants.status.active'));
    }
}
