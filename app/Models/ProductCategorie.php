<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategorie extends Model
{
    use HasFactory;

    protected $hidden = ['created_at','updated_at','status'];

    protected $fillable = ['name','status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
