<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Package
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $number_of_programs
 * @property $amount
 * @property $status_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Status $status
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Package extends Model
{
    use SoftDeletes;
    use HasFactory;  

    static $rules = [
      'name' => 'required',
      'description' => 'required',
      'number_of_programs' => 'required',
      'amount' => 'required',
      'status_id' => 'required',
    ];
    protected $hidden = ['created_at','updated_at','deleted_at','stripe_id'];
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','description','number_of_programs','amount','status_id','user_id','orden','view_app'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->hasOne('App\Models\Status', 'id', 'status_id');
    }

    public function package_status(){
      return $this->belongsTo(Status::class,'status_id');
    }
    
    public function package_subscription(){
      return $this->hasMany(Subscription::class,'package_id');
    }

    public function prices(){
      return $this->hasMany(PackagesPrices::class,'packages_id');
      //INSERT INTO `packages_prices` (`id`, `packages_id`, `recurrences_id`, `amount`, `stripe_id`, `created_at`, `updated_at`) VALUES (NULL, '1', '2', '100', '12345678765', NULL, NULL);
    }

}
