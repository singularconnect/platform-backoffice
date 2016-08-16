<?php

namespace App\Applications\Api\Http\Controllers;

use App\Applications\Api\Exceptions\ApiException;
use App\Applications\Api\Exceptions\ApiValidationException;
use App\Domains\Exceptions\DuplicateKeyException;
use App\Domains\Repositories\RolesRepository;
use App\Domains\Repositories\UsersRepository;
use Illuminate\Http\Request;
use r;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class UsersController extends BaseController
{
    public function index(UsersRepository $repository, Request $request) {
        return parent::indexGeneral($repository, $request);
    }

    public function show(UsersRepository $repository, $ids) {
        return parent::showGeneral($repository, $ids);
    }

    public function store(UsersRepository $repository, Request $request) {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = $repository->validator($request->input());

        if( $validator->fails() )
            throw new ApiValidationException($validator->errors());
        else {
            try {

                $inputs = $validator->getData();
                $inputs['password'] = bcrypt($inputs['password']);

                $created = $repository->create($inputs);
                return $this->setStatusCode(201)->respondWithItem($created, $repository->getNewTransformer());

            }catch (DuplicateKeyException $ex) {
                throw new ApiException($ex->getMessage(), $ex->getCode(), $ex);
            }
        }
    }

    protected function attachRole(UsersRepository $usersRepository, RolesRepository $rolesRepository, $id, $idr) {
        if( !isset($id) || !$user = $usersRepository->findByID($id))
            throw new ResourceNotFoundException("User not found");

        if( !isset($idr) || !$role = $rolesRepository->findByID($idr))
            throw new ResourceNotFoundException("Role not found");

        if( !$user->hasRole($role->getKey()) ) {
            $user->roles()->attach($role);
            return $this->respondWithItem($rolesRepository->findByID($idr), $rolesRepository->getNewTransformer());
        }

        return $this->respondNotModified();
    }

    public function attachRoles(UsersRepository $usersRepository, RolesRepository $rolesRepository, $ids, $idsr) {
        if( !is_array($ids) )
            $ids = array_unique(explode(',', $ids));

        if( !is_array($idsr) )
            $idsr = array_unique(explode(',', $idsr));

        if( !count($users = $usersRepository->find($ids)) )
            throw new ResourceNotFoundException("Users not found");

        if( !count($roles = $rolesRepository->find($idsr)) )
            throw new ResourceNotFoundException("Roles not found");

        $metadata = [];

        $rolesnotfound = array_diff($idsr, $roles->modelKeys());
        $usersnotfound = array_diff($ids, $users->modelKeys());

        if( count($rolesnotfound) )
            $metadata['roles_not_found'] = $rolesnotfound;

        if( count($usersnotfound) )
            $metadata['users_not_found'] = $usersnotfound;

        foreach($users as $user) {
            $idsrfine = !isset($metadata['roles_not_found']) ? $idsr : array_diff($idsr, $metadata['roles_not_found']);
            $roleshave = array_intersect($user->roles->modelKeys(), $idsrfine);

            if( count($roleshave) == count($idsrfine) ) {
                $metadata['users_not_modified'] = isset($metadata['users_not_modified']) ? $metadata['users_not_modified'] : [];
                $metadata['users_not_modified'][] = $user->getKey();
                continue;
            }

            $user->roles()->attach($roles->modelKeys());
        }

        if( isset($metadata['users_not_modified']) && count($metadata['users_not_modified']) == $users->count() ) {
            if( isset($metadata['roles_not_found']) || isset($metadata['users_not_found']) )
                return $this->respondWithCollection($usersRepository->find($ids), $usersRepository->getNewTransformer(), $metadata);

            return $this->respondNotModified();
        }

        return $this->respondWithCollection($usersRepository->find($ids), $usersRepository->getNewTransformer(), $metadata);
    }

    public function detachRole(UsersRepository $usersRepository, RolesRepository $rolesRepository, $id, $idr) {
        if( !isset($id) || !$user = $usersRepository->findByID($id))
            throw new ResourceNotFoundException("User not found");

        if( !isset($idr) || !$role = $rolesRepository->findByID($idr))
            throw new ResourceNotFoundException("Role not found");

        if( $user->hasRole($role->getKey()) ) {
            $user->roles()->detach($role);
            return $this->respondWithItem($rolesRepository->findByID($idr), $rolesRepository->getNewTransformer());
        }

        return $this->respondNotModified();
    }

    public function detachRoles(UsersRepository $usersRepository, RolesRepository $rolesRepository, $ids, $idsr) {
        if( !is_array($ids) )
            $ids = array_unique(explode(',', $ids));

        if( !is_array($idsr) )
            $idsr = array_unique(explode(',', $idsr));

        if( !count($users = $usersRepository->find($ids)) )
            throw new ResourceNotFoundException("Users not found");

        if( !count($roles = $rolesRepository->find($idsr)) )
            throw new ResourceNotFoundException("Roles not found");

        $metadata = [];

        $rolesnotfound = array_diff($idsr, $roles->modelKeys());
        $usersnotfound = array_diff($ids, $users->modelKeys());

        if( count($rolesnotfound) )
            $metadata['roles_not_found'] = $rolesnotfound;

        if( count($usersnotfound) )
            $metadata['users_not_found'] = $usersnotfound;

        foreach($users as $user) {
            $idsrfine = !isset($metadata['roles_not_found']) ? $idsr : array_diff($idsr, $metadata['roles_not_found']);
            $roleshave = array_intersect($user->roles->modelKeys(), $idsrfine);

            if( !count($roleshave) ) {
                $metadata['users_not_modified'] = isset($metadata['users_not_modified']) ? $metadata['users_not_modified'] : [];
                $metadata['users_not_modified'][] = $user->getKey();
                continue;
            }

            $user->roles()->detach($roles->modelKeys());
        }

        if( isset($metadata['users_not_modified']) && count($metadata['users_not_modified']) == $users->count() ) {
            if( isset($metadata['roles_not_found']) || isset($metadata['users_not_found']) )
                return $this->respondWithCollection($usersRepository->find($ids), $usersRepository->getNewTransformer(), $metadata);

            return $this->respondNotModified();
        }

        return $this->respondWithCollection($usersRepository->find($ids), $usersRepository->getNewTransformer(), $metadata);
    }
}