<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id', 'image', 'status'
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

    public function getImageAttribute()
    {
        $imagePath = config('constants.image.post') . DIRECTORY_SEPARATOR . $this->attributes['image'];
        $disk = Storage::disk(config('constants.image.driver'));
        if (!empty($this->attributes['image']) && $disk->exists($imagePath)) {
            $fetchImage = config('app.url').Storage::url($imagePath);
        } else {
            $fetchImage = config('constants.image.defaultImage');
        }

        return $fetchImage;
    }
}
