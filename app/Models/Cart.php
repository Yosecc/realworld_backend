<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

     protected $fillable = ['products_id','count','color_id','talle_id'];

     protected $hidden = ['created_at','updated_at'];

     protected $with = ['product'];

     public function product()
     {
        return $this->belongsTo(Products::class, 'products_id');
     }

     public function color()
     {
        return $this->belongsTo(ProductsColores::class, 'color_id');
     }

     public function talle()
     {
        return $this->belongsTo(ProductsTalles::class, 'talle_id');
     }
}
