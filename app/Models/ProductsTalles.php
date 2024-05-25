<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsTalles extends Model
{
    use HasFactory;

    protected $hidden = ['created_at','updated_at','status','products_id'];

    protected $fillable =  ['description','status'];
}
