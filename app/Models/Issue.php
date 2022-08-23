<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'title', 'politician_id','issue_category_id', 'content', 'status', 'created_at', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function politician()
    {
        return $this->belongsTo(Politician::class);
    }

    public function issue_category()
    {
        return $this->belongsTo(IssueCategory::class);
    }


    public static function boot()
    {
        parent::boot();
        // registering a callback to be executed upon the creation of an activity AR
        static::creating(function ($issue) {
        // produce a slug based on the activity title
        $slug = Str::slug($issue->name);
        // check to see if any other slugs exist that are the same & count them
        $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        // if other slugs exist that are the same, append the count to the slug
        $issue->slug = $count ? "{$slug}-{$count}" : $slug;
        });

        static::updating(function ($issue) {
            // produce a slug based on the activity name
            $slug = Str::slug($issue->name);
            // check to see if any other slugs exist that are the same & count them
            // $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->where('id','!=',$this->id)->count()>1;
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            // if other slugs exist that are the same, append the count to the slug
            $issue->slug = $count ? "{$slug}-{$count}" : $slug;
            });
    }
}
