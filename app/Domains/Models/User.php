<?php

namespace App\Domains\Models;

use App\Applications\Api\Transformers\UserTransfomer;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Contracts\EntrustUserInterface;
use \App\Domains\Traits\EntrustUserTrait;

class User extends GenericModel implements AuthenticatableContract, CanResetPasswordContract, EntrustUserInterface {
    use Authenticatable, CanResetPassword, EntrustUserTrait;

    public $timestamps = true;

    public static $tablename = 'users';
    protected $table = 'users';

    public static $field_orderby_default = 'name';
    public static $field_search_default = 'name';

    public static $rules = [
        'default' => [
            'name' => 'required|string|between:3,30',
            'email' => 'required|email',
            'password' => 'required|between:5,20'
        ],
        'login' => [
            'email' => 'required|email',
            'password' => 'required|between:5,20',
            'remember' => 'boolean'
        ],
        'search_fields' => ['name', 'email']
    ];

    public static $transformers = [
        'default' => UserTransfomer::class
    ];

    public function roles() {
        return $this->belongsToMany('\App\Domains\Models\Role');
    }
}