<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['title','video','image','status','isCollection','fill'];
    
    protected $hidden = ['created_at', 'updated_at'];


    protected $casts = [
        // 'isCollection' => 'boolean',
        // 'status' => 'boolean',
    ];

    public function categorias(){
        return $this->hasMany(VideosCategorias::class,'videos_id');
    }
}