<?php

namespace App\Domains\Models;

use App\Domains\Transformers\PermissionTransfomer;
use App\Domains\Traits\EntrustPermissionTrait;
use Zizaco\Entrust\Contracts\EntrustPermissionInterface;

class Permission extends GenericModel implements EntrustPermissionInterface {
    use EntrustPermissionTrait;

    public static $tablename = 'permissions';
    protected $table = 'permissions';

    public static $field_orderby_default = 'display_name';
    public static $field_search_default = 'display_name';

    public static $rules = [
        'search_fields' => ['display_name']
    ];

    public static $transformers = [
        'default' => PermissionTransfomer::class
    ];

    protected $touches = ['roles'];

    public function roles() {
        return $this->belongsToMany('\App\Domains\Models\Role');
    }
}