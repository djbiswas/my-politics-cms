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

    protected $metaTable = 'Politician_metas'; 

    protected $metaKeyName = 'politician_id';
    
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
        if (!empty($this->attributes['affiliation_icon'])) {
            $imagePath = config('constants.image.politician') . DIRECTORY_SEPARATOR . $this->attributes['affiliation_icon'];
            $fetchIcon = config('app.url').Storage::url($imagePath);
        } else {
            $fetchIcon = config('constants.image.defaultImage');
        }
        return $fetchIcon;
    }

    public function getImageAttribute()
    {
        if (!empty($this->attributes['image'])) {
            $imagePath = config('constants.image.politician') . DIRECTORY_SEPARATOR . $this->attributes['image'];
            $fetchImage = config('app.url').Storage::url($imagePath);
        } else {
            $fetchImage = config('constants.image.defaultImage');
        }

        return $fetchImage;
    }
}
