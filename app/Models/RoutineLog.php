<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RoutineLog
 *
 * @property $id
 * @property $subscription_program_day_routine_id
 * @property $repetitions
 * @property $weight
 * @property $user_id
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property SubscriptionProgramDayRoutine $subscriptionProgramDayRoutine
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class RoutineLog extends Model
{
    use SoftDeletes;

    static $rules = [
		'subscription_program_day_routine_id' => 'required',
		'repetitions' => 'required',
		'weight' => 'required',
		'user_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['subscription_program_day_routine_id','repetitions','weight','user_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscriptionProgramDayRoutine()
    {
        return $this->hasOne('App\Models\SubscriptionProgramDayRoutine', 'id', 'subscription_program_day_routine_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    

}
