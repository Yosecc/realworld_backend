<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Program
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $program_category_id
 * @property $video
 * @property $number_of_days
 * @property $image
 * @property $popular
 * @property $recommended
 * @property $status_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property ProgramCategory $programCategory
 * @property Status $status
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Program extends Model
{
    use SoftDeletes;

    static $rules = [
		'name' => 'required',
		'description' => 'required',
		'program_category_id' => 'required',
		'video' => 'required',
		'number_of_days' => 'required',
		'image' => 'required',
		'status_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['isCarousel','name','description','program_category_id','video','number_of_days','image','popular','recommended','status_id','user_id','stripe_id','price_stripe_id','amount','amount_description','is_free'];

    protected $hidden = ['updated_at','stripe_id','price_stripe_id'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function programCategory()
    {
        return $this->hasOne('App\Models\ProgramCategory', 'id', 'program_category_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->hasOne('App\Models\Status', 'id', 'status_id');
    }

    public function program_category(){
        return $this->belongsTo(ProgramCategory::class);
    }

    public function program_status(){
        return $this->belongsTo(Status::class,'status_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function details(){
        return $this->hasMany(ProgramDay::class,'program_id');
    }

    public function details_program_day_routine(){
        return $this->hasMany(ProgramDayRoutine::class,'program_id');
    }

    public function exercises(){
        return $this->hasMany(Exercise::class,'program_id');
    }

    public function subscription_programs(){
        return $this->hasMany(SubscriptionProgram::class,'program_id');
    }

    public function subscription_program_day_routines(){
        return $this->hasMany(SubscriptionProgramDayRoutine::class,'program_id');
    }

    public function categorias(){
        return $this->hasMany(ProgramsCategorias::class,'programs_id');
    }

    public function scopeSearch($query, $search)
    {
        if($search)
            $query->where('name','like','%'.$search.'%');
    }
    

}
