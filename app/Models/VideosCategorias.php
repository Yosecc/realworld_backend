<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideosCategorias extends Model
{
    use HasFactory;

    protected $fillable = ['videos_id','categories_id'];

    public function categorie(){
        return $this->belongsTo(Categorie::class);
    }

}
