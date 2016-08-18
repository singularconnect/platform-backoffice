<?php

namespace App\Domains\Models;

use App\Domains\Transformers\ExchangeTransfomer;

class Exchange extends GenericModel {
    public $incrementing = false;

    public static $tablename = 'exchanges';
    protected $table = 'exchanges';

    //is array, but here is int for compatibility purpouses
    protected $keyType = 'int';

    public static $transformers = [
        'default' => ExchangeTransfomer::class
    ];

    public function exchange_historic() {
        return $this->belongsTo('\App\Domains\Models\ExchangeHistoric', 'exchange_historic_id');
    }
}