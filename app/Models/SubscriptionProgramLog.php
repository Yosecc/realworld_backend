<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionProgramLog extends Model
{
    use HasFactory;

    public function log_deatils()
    {
        return $this->hasMany(SubscriptionProgramLogDetail::class, 'subscription_program_logs_id');
    }
}
