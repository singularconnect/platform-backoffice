<?php

namespace App\Domains\Repositories;

use App\Domains\Models\Currency;

class CurrenciesRepository extends CommonRepository {
    protected $modelClass = Currency::class;
}