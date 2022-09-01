<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoliticianVotingAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'politician_id', 'date'
    ];


    public function politician()
    {
        return $this->belongsTo(Politician::class);
    }

    public function user()
    {
        return $this->belongsTo(Politician::class);
    }


}
