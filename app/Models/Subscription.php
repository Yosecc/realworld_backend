<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Subscription
 *
 * @property $id
 * @property $package_id
 * @property $user_id
 * @property $status_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Package $package
 * @property Status $status
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Subscription extends Model
{
    use SoftDeletes;
    use HasFactory;

    static $rules = [
		'package_id' => 'required',
		'user_id' => 'required',
		'status_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['package_id','user_id','status_id','packages_price_id','stripe_status','stripe_id','name','quantity','trial_ends_at','ends_at'];

    protected $with = ['package'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function package()
    {
        return $this->hasOne('App\Models\Package', 'id', 'package_id');
    }

    public function packagestripe()
    {
        return $this->hasOne('App\Models\Package', 'stripe_id', 'name');
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
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    

    public function price()
    {
        return $this->hasOne(PackagesPrices::class, 'stripe_id','stripe_price');
    }
    public function packagesPrice()
    {
        return $this->hasOne(PackagesPrices::class, 'id','packages_price_id');
    }
    
    public function programs()
    {
        return $this->hasMany(SubscriptionProgram::class,'subscription_id');
    }

}
