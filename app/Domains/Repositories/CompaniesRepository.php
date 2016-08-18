<?php

namespace App\Domains\Repositories;

use App\Domains\Models\Company;

class CompaniesRepository extends CommonRepository {
    protected $modelClass = Company::class;
}