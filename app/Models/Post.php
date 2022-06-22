<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'politician_id', 'content', 'gif', 'image', 'video', 'status'
    ];

    /**
     *
     * @param type $query
     * @return type Illuminate\Support\Collection
     */
    public function scopeOnlyActive($query)
    {
        return $query->where('status', config('constants.status.active'));
    }
    
    public function getImageAttribute()
    {
        if (Str::of($this->attributes['image'], 'uploads')) {
            $image = Str::of($this->attributes['image'])->explode('/');
            $imagePath = config('constants.image.uploads') . DIRECTORY_SEPARATOR . $image['1'];
        } else {
            $imagePath = config('constants.image.post_image') . DIRECTORY_SEPARATOR . $this->attributes['image'];
        }
        
        $disk = Storage::disk(config('constants.image.driver'));
        if (!empty($this->attributes['avatar']) && $disk->exists($imagePath)) {
            $fetchImage = Storage::url($imagePath);
        } else {
            $fetchImage = config('constants.image.defaultImage');
        }

        return $fetchImage;
    }

    public function getVideoAttribute()
    {
        if (Str::of($this->attributes['video'], 'uploads')) {
            $video = Str::of($this->attributes['video'])->explode('/');
            $videoPath = config('constants.image.uploads') . DIRECTORY_SEPARATOR . $video['1'];
        } else {
            $videoPath = config('constants.image.post_video') . DIRECTORY_SEPARATOR . $this->attributes['video'];
        }
        
        $disk = Storage::disk(config('constants.image.driver'));
        if (!empty($this->attributes['avatar']) && $disk->exists($videoPath)) {
            $fetchVideo = Storage::url($videoPath);
        } else {
            $fetchVideo = config('constants.image.defaultImage');
        }

        return $fetchVideo;
    }
}
