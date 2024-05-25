<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersProducts extends Model
{
    use HasFactory;

    protected $fillable = ['products_id','count','talle_id','color_id','price'];

    protected $hidden = ['updated_at','order_id','created_at','talle_id','color_id','products_id'];

    protected $with = ['product','talle','color'];

    public function product()
    {
        return $this->belongsTo(Products::class,'products_id')->without(['categorie','price','priceOffert','talles','colores']);
    }

    public function talle()
    {
        return $this->belongsTo(ProductsTalles::class, 'talle_id');
    }

    public function color()
    {
        return $this->belongsTo(ProductsColores::class, 'color_id');
    } 
}
