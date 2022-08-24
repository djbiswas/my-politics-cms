<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'politician_id', 'content', 'gif', 'status', 'comment_status', 'images', 'videos', 'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function politican()
    {
        return $this->belongsTo(Politician::class);
    }

    public function politician()
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

    public function userTrust()
    {
        return $this->hasMany(UserTrust::class, 'responded_id', 'id');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'm_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id', 'id');
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
