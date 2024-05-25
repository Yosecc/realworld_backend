<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagePricePrograms extends Model
{
    use HasFactory;

    protected $fillable = ['packages_prices_id','program_id'];

    public function program(){
        return $this->belongsTo(Program::class)->where('status_id',1);
    }
}
