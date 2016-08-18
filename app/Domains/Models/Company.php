<?php
namespace App\Domains\Models;

use App\Domains\Transformers\CompanyTransfomer;

class Company extends GenericModel {
    public static $tablename = 'companies';
    protected $table = 'companies';

    public static $rules = [
        'default' => [
            'id' => 'required|alpha_num|max:50',
            'domain' => 'required|string|regex:/^([a-z0-9]+(\-[a-z0-9]+)*\.)+[a-z]{2,}$/',
            'database_name' => 'required|string',
            'database_server_tag' => 'required|string',
            'status' => 'required|in:STARTED,CREATED,CONFIGURATED,SEEDED,READY'
        ]
    ];

    public static $transformers = [
        'default' => CompanyTransfomer::class
    ];
}