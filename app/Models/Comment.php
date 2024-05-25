<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment
 *
 * @property $id
 * @property $reason
 * @property $description
 * @property $type
 * @property $subscription_id
 * @property $user_id
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Subscription $subscription
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Comment extends Model
{
    use SoftDeletes;

    static $rules = [
		'reason' => 'required',
		'description' => 'required',
		'type' => 'required',
		'subscription_id' => 'required',
		'user_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reason','description','type','subscription_id','user_id'];


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
