<?php
namespace App\Applications\Api\Http\Controllers;

use App\Domains\Repositories\ExchangesHistoricsRepository;
use Illuminate\Http\Request;

class ExchangesHistoricsController extends BaseController
{
    public function index(ExchangesHistoricsRepository $repository, Request $request) {
        return parent::indexGeneral($repository, $request);
    }

    public function show(ExchangesHistoricsRepository $repository, $ids) {
        return parent::showGeneral($repository, $ids);
    }
}