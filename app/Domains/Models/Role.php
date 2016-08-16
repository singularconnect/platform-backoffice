<?php

namespace App\Domains\Models;

use App\Applications\Api\Transformers\RoleTransfomer;
use App\Domains\Traits\EntrustRoleTrait;
use Zizaco\Entrust\Contracts\EntrustRoleInterface;

class Role extends GenericModel implements EntrustRoleInterface {
    use EntrustRoleTrait;

    public static $tablename = 'roles';
    protected $table = 'roles';

    protected $appends = [
        'display_name',
        'description'
    ];

    public static $transformers = [
        'default' => RoleTransfomer::class
    ];

    protected $touches = ['users'];

    public function permissions() {
        return $this->belongsToMany('\App\Domains\Models\Permission');
    }

    public function users() {
        return $this->belongsToMany('\App\Domains\Models\User');
    }

    //for entrust things
    public function perms() {
        return $this->permissions();
    }

    public function getDisplayNameAttribute() {
        return trans($this->getTable() . '.' . $this->getKey());
    }

    public function getDescriptionAttribute() {
        return trans($this->getTable() . '.' . $this->getKey() . '_description');
    }
}
