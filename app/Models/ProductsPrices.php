<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsPrices extends Model
{
    use HasFactory;

    protected $hidden = ['created_at','updated_at','status','stripe_id','id','products_id'];
}
