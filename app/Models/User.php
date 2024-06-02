<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;


class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'gender_id',
        'date_of_birth',
        'country_id',
        'address',
        'telephone',
        'profile_photo_path',
        'experience_id',
        'reason_id',
        'frequency_id',
        'exercise_place_id',
        'state_id',
        'city',
        'postal_code',
        'size',
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
