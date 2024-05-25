<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $with = ['country','state'];


    public function gender(){
        return $this->belongsTo(Gender::class,'gender_id');
    }


    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
    public function state(){
        return $this->belongsTo(State::class,'state_id');
    }

    
    public function sendPasswordResetNotification($token)
    {

        $url = 'http://localhost:8000/?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class,'user_id')->whereIn('stripe_status',['active','trialing'])->latest();
    }

    public function subscriptionProgramNoRecurrence()
    {
        return $this->hasMany(SubscriptionProgram::class,'user_id')->where('is_recurrente',0);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

   
    public function order()
    {
        return $this->hasMany(Orders::class, 'user_id');
    }

    public function canAccessFilament(): bool
    {
        return true;
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
