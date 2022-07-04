<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Kodeine\Metable\Metable;

class Politician extends Model
{
    use HasFactory, SoftDeletes;

    use Metable;

    protected $metaTable = 'user_metas'; 

    protected $metaKeyName = 'user_id';
    
    protected $disableFluentMeta = true;

    protected $fillable = [
        'name', 'title', 'name_alias', 'affiliation', 'affiliation_icon', 'position', 'politician_description', 
        'image', 'created_by', 'updated_by', 'status'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function votes()
    {
        return $this->hasMany(PoliticanVote::class);
    }

    public function politicianMetas()
    {
        return $this->hasMany(PoliticianMeta::class, 'politician_id', 'id');
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
    
    public function getAffiliationIconAttribute()
    {
        if (Str::of($this->attributes['affiliation_icon'], 'uploads')) {
            $image = Str::of($this->attributes['affiliation_icon'])->explode('/');
            $imagePath = config('constants.image.uploads') . DIRECTORY_SEPARATOR . $image['1'];
        } else {
            $imagePath = config('constants.image.affiliation_icon') . DIRECTORY_SEPARATOR . $this->attributes['affiliation_icon'];
        }
        
        $disk = Storage::disk(config('constants.image.driver'));
        if (!empty($this->attributes['affiliation_icon']) && $disk->exists($imagePath)) {
            $fetchIcon = Storage::url($imagePath);
        } else {
            $fetchIcon = config('constants.image.defaultImage');
        }

        return $fetchIcon;
    }

    public function getImageAttribute()
    {
        if (Str::of($this->attributes['image'], 'uploads')) {
            $image = Str::of($this->attributes['image'])->explode('/');
            $imagePath = config('constants.image.uploads') . DIRECTORY_SEPARATOR . $image['1'];
        } else {
            $imagePath = config('constants.image.politican') . DIRECTORY_SEPARATOR . $this->attributes['image'];
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
