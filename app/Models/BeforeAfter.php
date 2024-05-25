<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeforeAfter extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','url_foto','type','pose'];

    public function scopeBefore($query)
    {
        return $query->where('type', 'before');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function subscripcion()
    {
        return $this->belongsTo(SubscriptionProgram::class,'subscription_programs_id');
    }
}
