<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * An user has the following attributes:
     *
     * @name
     */
    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';


    // Seller and Buyer goint to inherit this attribute
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        //'verification_token',
    ];

    //***************** Mutators
    public function setNameAttribute($name){
        // Every time the name is recorded in database the name should be in lower case.
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute($name){

        // But when we retry this from database we wetru in UcWords.
        return ucwords($name);
    }

    public function setEmailAttribute($email){
        // every email should be lower case
        $this->attributes['email'] = strtolower($email);
    }
    
    //***************** END Mutators


    public function isVerified(){
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin(){
        return $this->admin == User::ADMIN_USER;
    }

    public static function generateVerificationCode(){
        return str_random(40);
    }
}
