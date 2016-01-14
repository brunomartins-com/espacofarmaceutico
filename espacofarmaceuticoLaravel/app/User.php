<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

//class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    //use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['babyName', 'babyBirthdate', 'babyGender', 'birthCertificate', 'name', 'rg', 'cpf', 'address', 'gender', 'number', 'complement', 'district', 'state', 'city', 'phone', 'mobile', 'email', 'password', 'token', 'type', 'active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function photos($usersId)
    {
        return Photos::where('usersId', '=', $usersId);
    }

    public function videos($usersId)
    {
        return Videos::where('usersId', '=', $usersId);
    }
}
