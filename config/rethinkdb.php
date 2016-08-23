<?php

return [

    'clustering' => env('APP_ENV') != 'production' || env('APP_ENV') != 'staging' ?
        'default' : [
        // configs per table - based on https://www.rethinkdb.com/api/javascript/reconfigure/
        'all_tables' => [
            'shards' => 1,
            'replicas' => [
                'sb_platform' => 1
            ],
            'primaryReplicaTag' => 'sb_platform'
        ]
    ]

];