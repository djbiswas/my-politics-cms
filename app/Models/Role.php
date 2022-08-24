<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id', 'role', 'status'
    ];


    public function role_permissions()
    {
        return $this->hasMany(RolePermission::class);
    }



    /**
     *
     * @param type $query
     * @return type Illuminate\Support\Collection
     */


    // public function scopeOnlyActive($query)
    // {
    //     return $query->where('status', config('constants.status.active'));
    // }

    // public function getImageAttribute()
    // {
    //     $imagePath = config('constants.image.role') . DIRECTORY_SEPARATOR . $this->attributes['image'];
    //     $disk = Storage::disk(config('constants.image.driver'));
    //     if (!empty($this->attributes['image']) && $disk->exists($imagePath)) {
    //         $fetchImage = config('app.url').Storage::url($imagePath);
    //     } else {
    //         $fetchImage = config('constants.image.defaultImage');
    //     }

    //     return $fetchImage;

    // }


    // public static function boot()
    // {
    //     parent::boot();
    //         // registering a callback to be executed upon the creation of an activity AR
    //         static::creating(function ($role) {
    //         // produce a slug based on the activity title
    //         $slug = Str::slug($role->role);
    //         // check to see if any other slugs exist that are the same & count them
    //         $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
    //         // if other slugs exist that are the same, append the count to the slug
    //         $role->slug = $count ? "{$slug}-{$count}" : $slug;
    //     });

    //     static::updating(function ($role) {
    //         // produce a slug based on the activity name
    //         $slug = Str::slug($role->role);
    //         // check to see if any other slugs exist that are the same & count them
    //         // $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->where('id','!=',$this->id)->count()>1;
    //         $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
    //         // if other slugs exist that are the same, append the count to the slug
    //         $role->slug = $count ? "{$slug}-{$count}" : $slug;
    //     });
    // }
}
