<?php
namespace App\Domains\Repositories;

use App\Domains\Models\Role;

class RolesRepository extends CommonRepository {
    protected $modelClass = Role::class;
}