<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandMessage extends Model
{
    use HasFactory;
    protected $fillable = ['demandId', 'userId', 'text'];
    protected $appends = ['date'];

    public function user()
    {
        return $this->hasOne(User::class,'id','userId');
    }

    public function getDateAttribute()
    {
        return Demand::timeAgo($this->attributes['created_at']);
    }
}
