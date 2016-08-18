<?php

namespace App\Domains\Models;

use App\Domains\Transformers\PermissionTransfomer;
use App\Domains\Traits\EntrustPermissionTrait;
use Zizaco\Entrust\Contracts\EntrustPermissionInterface;

class Permission extends GenericModel implements EntrustPermissionInterface {
    use EntrustPermissionTrait;

    public static $tablename = 'permissions';
    protected $table = 'permissions';

    protected $appends = [
        'display_name',
        'description'
    ];

    public static $transformers = [
        'default' => PermissionTransfomer::class
    ];

    protected $touches = ['roles'];

    public function roles() {
        return $this->belongsToMany('\App\Domains\Models\Role');
    }

    public function getDisplayNameAttribute() {
        return trans($this->getTable() . '.' . $this->getKey());
    }

    public function getDescriptionAttribute() {
        return trans($this->getTable() . '.' . $this->getKey() . '_description');
    }
}