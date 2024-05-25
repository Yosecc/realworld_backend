<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaymentHistory
 *
 * @property $id
 * @property $payment_date
 * @property $amount
 * @property $status_id
 * @property $subscription_id
 * @property $user_id
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Status $status
 * @property Subscription $subscription
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PaymentHistory extends Model
{
    use SoftDeletes;

    static $rules = [
		'payment_date' => 'required',
		'amount' => 'required',
		'status_id' => 'required',
		'subscription_id' => 'required',
		'user_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['payment_date','amount','status_id','subscription_id','user_id'];


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
    

}
