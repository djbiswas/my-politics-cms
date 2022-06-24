<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Rank extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'image', 'post_count', 'trust_percentage', 'long_desc', 'created_by', 'updated_by', 'status'
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
        if (!empty($this->attributes['image'])) {
            if (Str::contains($this->attributes['image'], 'uploads')) {
                $image = Str::of($this->attributes['image'])->explode('/');
                $imagePath = config('constants.image.uploads') . DIRECTORY_SEPARATOR . $image['1'];
            } else {
                $imagePath = config('constants.image.rank') . DIRECTORY_SEPARATOR . $this->attributes['image'];
            }
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
