<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FrequentlyAskedQuestion
 *
 * @property $id
 * @property $question
 * @property $answer
 * @property $user_id
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class FrequentlyAskedQuestion extends Model
{
    use SoftDeletes;

    static $rules = [
		'question' => 'required',
		'answer' => 'required',
		'user_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['question','answer','user_id','is_link'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    
    public function scopesearch($query, $search)
    {
      if($search)
        $query->where(function($query) use ($search){
          $query->where('question','like','%'.$search.'%')->orWhere('answer','like','%'.$search.'%');
        });
    }
}
