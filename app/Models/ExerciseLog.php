<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ExerciseLog
 *
 * @property $id
 * @property $description
 * @property $start
 * @property $finish
 * @property $exercise_id
 * @property $user_id
 * @property $status_id
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Exercise $exercise
 * @property Status $status
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ExerciseLog extends Model
{
    use SoftDeletes;

    static $rules = [
		'description' => 'required',
		'start' => 'required',
		'finish' => 'required',
		'exercise_id' => 'required',
		'user_id' => 'required',
		'status_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['description','start','finish','exercise_id','user_id','status_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function exercise()
    {
        return $this->hasOne('App\Models\Exercise', 'id', 'exercise_id');
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
    

}
