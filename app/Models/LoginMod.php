<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class LoginMod extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    //use TwoFactorAuthenticatable;
  //  public $timestamps = false;
    protected $table = "users";

    protected $fillable = [

        'name',
        'email',
        'password',


    ];

    protected $hidden = [
        'password',
        'remember_token',
        
        'two_factor_secret',
    ];

}
