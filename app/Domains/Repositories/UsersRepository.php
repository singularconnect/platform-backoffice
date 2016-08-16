<?php

namespace App\Domains\Repositories;

use App\Domains\Exceptions\DuplicateKeyException;
use App\Domains\Models\User;

class UsersRepository extends CommonRepository {
    protected $modelClass = User::class;

    public function create(array $data = []) {
        $existemail = $this->r()->getAll($data['email'], array('index' =>'email'))->count();

        if( $existemail )
            throw new DuplicateKeyException('email in ' . $this->getTableName() . ' table already exist', 101);

        else
            return parent::create($data);
    }
}