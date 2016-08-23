<?php

return [

    'clustering' => (env('APP_ENV') == 'production' and env('APP_ENV') == 'staging') ?
        'default' : [
        // configs per table - based on https://www.rethinkdb.com/api/javascript/reconfigure/
        'all_tables' => [
            'shards' => 1,
            'replicas' => [
                'default' => 1
            ],
            // primaryReplicaTag is the original docs/examples but with php is snake_case
            'primary_replica_tag' => 'default'
        ]
    ]

];