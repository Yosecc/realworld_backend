<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Exercise
 *
 * @property $id
 * @property $description
 * @property $program_id
 * @property $user_id
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Program $program
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Exercise extends Model
{
    use SoftDeletes;

    static $rules = [
		'description' => 'required',
		'program_id' => 'required',
		'user_id' => 'required',
    ];

    protected $casts = [
        'video' => 'array',
      ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['description','video','program_id','user_id'];


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

    public function exercise_user(){
        return $this->belongsTo(User::class, 'user_id');
     }
    

}
