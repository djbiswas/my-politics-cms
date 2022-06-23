<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rank_id', 'role_id', 'login', 'email', 'password', 'first_name', 'last_name', 'display_name', 'phone',
        'image', 'lock_rank', 'display_status', 'reg_status', 'status',
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

    public function getImageAttribute()
    {
        if (Str::of($this->attributes['image'], 'uploads')) {
            $image = Str::of($this->attributes['image'])->explode('/');
            $imagePath = config('constants.image.uploads') . DIRECTORY_SEPARATOR . $image['1'];
        } else {
            $imagePath = config('constants.image.user') . DIRECTORY_SEPARATOR . $this->attributes['image'];
        }
        
        $disk = Storage::disk(config('constants.image.driver'));
        if (!empty($this->attributes['avatar']) && $disk->exists($imagePath)) {
            $fetchImage = Storage::url($imagePath);
        } else {
            $fetchImage = config('constants.image.defaultImage');
        }

        return $fetchImage;
    }
}
