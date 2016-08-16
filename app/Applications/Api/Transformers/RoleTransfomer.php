<?php
namespace App\Applications\Api\Transformers;

use App\Domains\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransfomer extends TransformerAbstract {

    protected $availableIncludes = [
        'users', 'permissions'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Role $resource
     * @return array
     */
    public function transform(Role $resource)
    {
        return [
            'id' => $resource->id,
            'display_name' => $resource->display_name,
            'description' => $resource->description
        ];
    }

    public function includeUsers(Role $resource) {
        $users = $resource->users;

        return $this->collection($users, new UserTransfomer);
    }

    public function includePermissions(Role $resource) {
        $permissions = $resource->permissions;

        return $this->collection($permissions, new PermissionTransfomer);
    }

}