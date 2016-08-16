<?php
namespace App\Domains\Repositories;

use App\Domains\Models\Permission;

class PermissionsRepository extends CommonRepository {
    protected $modelClass = Permission::class;
}