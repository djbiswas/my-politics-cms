<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_comment_id', 'user_id', 'post_id ', 'comment', 'gif', 'image', 'status'
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
            $imagePath = config('constants.image.post_comment_image') . DIRECTORY_SEPARATOR . $this->attributes['image'];
        }
        
        $disk = Storage::disk(config('constants.image.driver'));
        if (!empty($this->attributes['image']) && $disk->exists($imagePath)) {
            $fetchImage = Storage::url($imagePath);
        } else {
            $fetchImage = config('constants.image.defaultImage');
        }

        return $fetchImage;
    }
}
