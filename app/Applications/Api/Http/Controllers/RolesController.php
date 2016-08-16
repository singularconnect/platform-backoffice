<?php

namespace App\Applications\Api\Http\Controllers;

use App\Domains\Repositories\RolesRepository;
use Illuminate\Http\Request;

class RolesController extends BaseController
{
    public function index(RolesRepository $repository, Request $request) {
        return parent::indexGeneral($repository, $request);
    }

    public function show(RolesRepository $repository, $ids) {
        return parent::showGeneral($repository, $ids);
    }
}