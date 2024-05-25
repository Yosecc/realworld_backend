<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = ['name','icon','order'];

    protected $hidden = ['created_at', 'updated_at'];

    public function programs()
    {
        return $this->hasMany(ProgramsCategorias::class,'categories_id');
    }

}
