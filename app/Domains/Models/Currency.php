<?php

namespace App\Domains\Models;

use App\Domains\Transformers\CurrencyTransfomer;

class Currency extends GenericModel {
    public static $tablename = 'currencies';
    protected $table = 'currencies';

    public static $rules = [
        'default' => [
            'code' => 'string|size:3|regex:/^[A-Z]{3}$/',
            'id' => 'required_without:code|string|size:3|regex:/^[A-Z]{3}$/',
            'format' => 'required|string|between:1,3',
            'name' => 'required|string|between:4,20'
        ]
    ];

    public static $transformers = [
        'default' => CurrencyTransfomer::class
    ];
}