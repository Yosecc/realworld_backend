<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $hidden = ['created_at','updated_at','status','stripe_id'];

    protected $with = ['categorie','price','priceOffert','imagenes','talles','colores'];

    protected $fillable = ['categorie_id','name','description','stock','status','price_last','price_offert_last'];


    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('name','LIKE','%'.$name.'%');
    }

    // public function productCategorie()
    public function categorie()
    {
        return $this->belongsTo(ProductCategorie::class, 'categorie_id');
    }

    // public function productsTalle()
    public function talles()
    {
        return $this->hasMany(ProductsTalles::class, 'products_id');
    }

    // public function productsColores()
    public function colores()
    {
        return $this->hasMany(ProductsColores::class, 'products_id');
    } 

    // public function productsImagenes()
    public function imagenes()
    {
        return $this->hasMany(ProductsImagenes::class, 'products_id');
    }

    public function productsPrices()
    {
        return $this->hasMany(ProductsPrices::class,'products_id');
    }

    public function price()
    {
        return $this->hasOne(ProductsPrices::class,'products_id')->where('status',1)->latest();
    }

    public function priceOffert()
    {
        return $this->hasOne(ProductsPrices::class,'id','price_id_offert')->where('status',1)->latestOfMany();
    }    
}
