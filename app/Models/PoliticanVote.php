<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoliticanVote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'politician_id', 'vote', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function politican()
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
