<?php

namespace App\Domains\Repositories;

use App\Domains\Models\Currency;
use brunojk\LaravelRethinkdb\Eloquent\Model;

class CurrenciesRepository extends CommonRepository {
    protected $modelClass = Currency::class;
}