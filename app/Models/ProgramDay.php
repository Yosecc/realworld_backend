<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProgramDay
 *
 * @property $id
 * @property $program_id
 * @property $name
 * @property $number
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Program $program
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProgramDay extends Model
{
    use SoftDeletes;

    static $rules = [
		'program_id' => 'required',
		'name' => 'required',
		'number' => 'required',
		'user_id' => 'required',
        'description' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['program_id','name','number','user_id','description'];

    protected $hidden = ['deleted_at','created_at','updated_at'];


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
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    
    public function program_day_user(){
        return $this->belongsTo(User::class, 'user_id');
     }

     public function exercise()
     {
        return $this->hasMany(ProgramDayRoutine::class, 'program_day_id');
     }



}
