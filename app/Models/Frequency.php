<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Frequency extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "frequencies";
    protected $fillable = ['description','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
