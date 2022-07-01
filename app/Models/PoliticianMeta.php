<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PoliticianMeta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'politican_metas';

    protected $fillable = [
        'politician_id',
        'type',
        'key',
        'value'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function politicians()
    {
        return $this->belongsTo(Politician::class);
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
