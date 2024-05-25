<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Status
 *
 * @property $id
 * @property $description
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Package[] $packages
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Status extends Model
{
    use SoftDeletes;
    use HasFactory;

    static $rules = [
		'description' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['description'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packages()
    {
        return $this->hasMany('App\Models\Package', 'status_id', 'id');
    }
    

}
