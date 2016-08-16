<?php

namespace App\Domains\Models;

use App\Applications\Api\Transformers\ExchangeHistoricTransfomer;

class ExchangeHistoric extends GenericModel {
    //false but in the boot function allow only created_at
    public $timestamps = false;

    public static $tablename = 'exchanges_historics';
    protected $table = 'exchanges_historics';

    public static $transformers = [
        'default' => ExchangeHistoricTransfomer::class
    ];

    public function exchange()  {
        return $this->hasOne('\App\Domains\Models\Exchange');
    }

    public static function boot() {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}