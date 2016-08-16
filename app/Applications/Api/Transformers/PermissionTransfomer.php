<?php
namespace App\Applications\Api\Transformers;

use App\Domains\Models\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransfomer extends TransformerAbstract {

    protected $availableIncludes = [
        'roles'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Permission $resource
     * @return array
     */
    public function transform(Permission $resource)
    {
        return [
            'id' => $resource->id,
            'display_name' => $resource->display_name,
            'description' => $resource->description,
        ];
    }

    public function includeRoles(Permission $resource) {
        $roles = $resource->roles;

        return $this->collection($roles, new RoleTransfomer);
    }

}