<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SubscriptionProgramDayRoutine
 *
 * @property $id
 * @property $subscription_programs_id
 * @property $program_days_id
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property ProgramDay $programDay
 * @property SubscriptionProgram $subscriptionProgram
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SubscriptionProgramDayRoutine extends Model
{
    use SoftDeletes;

    static $rules = [
		'subscription_programs_id' => 'required',
		'program_days_id' => 'required',
		'user_id' => 'required',
        'is_active' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['subscription_programs_id','program_day_id','user_id','program_id','is_active'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function programDay()
    {
        return $this->hasOne('App\Models\ProgramDay', 'id', 'program_days_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscriptionProgram()
    {
        return $this->hasOne('App\Models\SubscriptionProgram', 'id', 'subscription_programs_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    
    public function program_day(){
        return $this->belongsTo(ProgramDay::class,'program_day_id');
    }

    public function subscription_program(){
        return $this->belongsTo(SubscriptionProgram::class,'subscription_programs_id');
    }

}
