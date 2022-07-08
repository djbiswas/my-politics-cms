<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id', 'name', 'status'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
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
    
    public function getNameAttribute()
    {
        if (Str::contains($this->attributes['name'], 'uploads')) {
            $video = Str::of($this->attributes['name'])->explode('/');
            $videoPath = config('constants.image.uploads') . DIRECTORY_SEPARATOR . $video['1'];
        } else {
            $videoPath = config('constants.image.post') . DIRECTORY_SEPARATOR . $this->attributes['name'];
        }
        
        $disk = Storage::disk(config('constants.image.driver'));
        if (!empty($this->attributes['name']) && $disk->exists($videoPath)) {
            $fetchVideo = config('app.url').Storage::url($videoPath);
        } else {
            $fetchVideo = config('constants.image.defaultImage');
        }

        return $fetchVideo;
    }
}
