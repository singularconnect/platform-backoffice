<?php

namespace App\Applications\Api\Http\Controllers;

use App\Domains\Repositories\PermissionsRepository;
use Illuminate\Http\Request;
use r;

class PermissionsController extends BaseController
{
    public function index(PermissionsRepository $repository, Request $request) {
        return parent::indexGeneral($repository, $request);
    }

    public function show(PermissionsRepository $repository, $ids) {
        return parent::showGeneral($repository, $ids);
    }
}