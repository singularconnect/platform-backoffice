<?php
namespace App\Applications\Api\Transformers;

use App\Domains\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransfomer extends TransformerAbstract {

    protected $availableIncludes = [
        'roles'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param User $resource
     * @return array
     */
    public function transform(User $resource)
    {
        return [
            'id' => $resource->id,
            'name' => $resource->name,
            'email' => $resource->email,
        ];
    }

    public function includeRoles(User $resource) {
        $roles = $resource->roles;

        return $this->collection($roles, new RoleTransfomer);
    }

}