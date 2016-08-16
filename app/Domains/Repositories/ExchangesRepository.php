<?php

namespace App\Domains\Repositories;

use App\Domains\Models\Exchange;

class ExchangesRepository extends CommonRepository {
    protected $modelClass = Exchange::class;

    public function update($model, array $data = []){
        if( array_key_exists('id', $data) )
            unset($data['id']);

        return parent::update($model, $data);
    }

    public function findByID($id, $fail = true) {
        return parent::findByID($id, true, $fail);
    }

    public function find($values, array $indexes = ['index' => 'id'], $is_compounded = true) {
        if( count($values) && is_array($values[0]) )
            return parent::find($values, $indexes, false);

        return parent::find($values, $indexes, $is_compounded);
    }
}