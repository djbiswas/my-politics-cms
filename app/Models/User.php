<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Kodeine\Metable\Metable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    use Metable;

    protected $metaTable = 'user_metas';

    protected $metaKeyName = 'user_id';

    protected $disableFluentMeta = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rank_id', 'role_id', 'login', 'email', 'password', 'first_name', 'last_name', 'display_name', 'phone',
        'image', 'lock_rank', 'display_status', 'reg_status', 'status', 'registered_date', 'completed_step'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'registered_date' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userMeta()
    {
        return $this->hasMany(UserMeta::class, 'user_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function votes()
    {
        return $this->hasMany(PoliticanVote::class);
    }

    public function ranks()
    {
        return $this->belongsTo(Rank::class, 'rank_id', 'id');
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
        $imagePath = config('constants.image.user') . DIRECTORY_SEPARATOR . $this->attributes['image'];
        $disk = Storage::disk(config('constants.image.driver'));
        if (!empty($this->attributes['image']) && $disk->exists($imagePath)) {
            $fetchImage = config('app.url') . Storage::url($imagePath);
        } else {
            $fetchImage = config('constants.image.defaultImage');
        }

        return $fetchImage;
    }
}
