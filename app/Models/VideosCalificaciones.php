<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideosCalificaciones extends Model
{
    use HasFactory;

    protected $fillable = ['videos_id','user_id'];
}
