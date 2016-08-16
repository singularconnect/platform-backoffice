<?php

namespace App\Domains\Models;

use App\Applications\Api\Transformers\TranslationTransfomer;

class Translation extends GenericModel {
    public static $tablename = 'translations';
    protected $table = 'translations';

    public static $rules = [
        'default' => [
            'id' => 'required_without_all:origin,language,context,key',
            'origin' => 'required|in:app,plt',
            'language' => 'required|string|between:2,5',
            'context' => 'required|string|between:2,15',
            'key' => 'required|alpha_dash|max:60',
            'target' => 'required|string'
        ]
    ];

    public static $transformers = [
        'default' => TranslationTransfomer::class
    ];
}
