<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramsCalificaciones extends Model
{
    use HasFactory;

    protected $fillable = ['programs_id','user_id'];

}
