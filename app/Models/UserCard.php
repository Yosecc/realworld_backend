<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserCard
 *
 * @property $id
 * @property $number
 * @property $name
 * @property $cvv
 * @property $expiration_date
 * @property $user_id
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class UserCard extends Model
{
    use SoftDeletes;

    static $rules = [
		'number' => 'required',
		'name' => 'required',
		'cvv' => 'required',
		'expiration_date' => 'required',
		'user_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['number','name','cvv','expiration_date','user_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    

}
