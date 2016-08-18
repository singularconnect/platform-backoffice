<?php

namespace App\Domains\Models;

use App\Domains\Transformers\TranslationTransfomer;

class Translation extends GenericModel {
    public static $tablename = 'translations';
    protected $table = 'translations';

    public static $rules = [
        'default' => [
            'key' => 'required|string|max:60|regex:/^(\w+\.?\w+)+$/',
            'language' => 'string|between:2,5',
            'context' => 'string|between:2,15',
            'target' => 'required|string'
        ]
    ];

    public static $transformers = [
        'default' => TranslationTransfomer::class,
    ];
}
