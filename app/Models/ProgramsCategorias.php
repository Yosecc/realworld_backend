<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramsCategorias extends Model
{
    use HasFactory;

    protected $fillable = ['programs_id','categories_id'];

    public function program(){
        return $this->belongsTo(Program::class,'programs_id');
    }
}
