<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProgramDayRoutine
 *
 * @property $id
 * @property $title
 * @property $video
 * @property $sets
 * @property $repetitions
 * @property $program_day_id
 * @property $status_id
 * @property $user_id
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property ProgramDay $programDay
 * @property Status $status
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProgramDayRoutine extends Model
{
    use SoftDeletes;

    static $rules = [
		'title' => 'required',
		'video' => 'required',
		'sets' => 'required',
		'repetitions' => 'required',
		'program_day_id' => 'required',
		'status_id' => 'required',
		'user_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title','video','sets','repetitions','program_day_id','status_id','user_id','videos_id'];
    protected $hidden = ['deleted_at','created_at','updated_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function programDay()
    {
        return $this->hasOne('App\Models\ProgramDay', 'id', 'program_day_id');
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

    
    public function program_day(){
        return $this->belongsTo(ProgramDay::class,'program_day_id');
    }

    public function video_()
    {
        return $this->belongsTo(Video::class,'videos_id');
    }

    public function programSuscription()
    {
        return $this->hasOne(SubscriptionProgram::class,'program_id','program_id')->where('subscription_id', Auth::user()->subscription->id);
    }

    public function logs()
    {
        return $this->programSuscription();
    }
    

}
