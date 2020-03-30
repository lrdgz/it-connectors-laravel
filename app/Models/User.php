<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\HasGravatar;


class User extends Authenticatable
{
    use
        Notifiable,
        HasApiTokens,
        SoftDeletes,
        HasGravatar;


    /*SIMPLE ROLES*/
    const ADMIN = 'admin';
    const USER = 'user';
    const SUPPORT = 'support';
    const AGENT = '=agent';

    const USERS_ROLES = [self::ADMIN, self::USER, self::SUPPORT, self::AGENT];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'access', 'active',
        'verified_email', 'verified_mobile', 'email_token', 'mobile_token', 'email_verified_at',
        'last_logged_at','last_login_ip'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'email_token', 'mobile_token',
        'pin'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_logged_at'    => 'datetime',
    ];

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }


}
