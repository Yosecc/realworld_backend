<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SubscriptionProgram
 *
 * @property $id
 * @property $subscription_id
 * @property $program_id
 * @property $status_id
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Program $program
 * @property Status $status
 * @property Subscription $subscription
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SubscriptionProgram extends Model
{
    use SoftDeletes;

    static $rules = [
		'subscription_id' => 'required',
		'program_id' => 'required',
		'status_id' => 'required',
		'user_id' => 'required',
        'is_active' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['subscription_id','program_id','status_id','user_id','is_active','is_recurrente'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function program()
    {
        return $this->hasOne('App\Models\Program', 'id', 'program_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->hasOne('App\Models\Status', 'id', 'status_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscription()
    {
        return $this->hasOne('App\Models\Subscription', 'id', 'subscription_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function fotos()
    {
        return $this->hasMany(BeforeAfter::class, 'subscription_programs_id');
    }

     public function logs()
    {
        return $this->hasMany(SubscriptionProgramLog::class, 'subscription_programs_id')->with(['log_deatils']);
    }
    

}
