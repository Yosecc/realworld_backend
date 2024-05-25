<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsImagenes extends Model
{
    use HasFactory;

    protected $hidden = ['created_at','updated_at','id','products_id'];

    protected $fillable = ['image'];

    // protected $casts = [
    //     'image' => 'array',
    // ];
}
