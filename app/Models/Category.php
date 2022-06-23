<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'icon', 'created_by', 'updated_by', 'status'
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

    public function getIconAttribute()
    {
        $fetchPath = "";
        if (!empty($this->attributes['icon'])) {
            if (Str::contains($this->attributes['icon'], 'uploads')) {
                $image = Str::of($this->attributes['icon'])->explode('/');
                $fetchPath = config('constants.image.uploads') . DIRECTORY_SEPARATOR . $image['1'];
            } else {
                $imagePath = config('constants.image.category') . DIRECTORY_SEPARATOR . $this->attributes['icon'];
                $fetchPath = Storage::url($imagePath);
            }
        }
        return $fetchPath;
    }
}
