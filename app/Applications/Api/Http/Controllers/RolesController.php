<?php

namespace App\Applications\Api\Http\Controllers;

use App\Domains\Repositories\I18nRepository;
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

    public function test(I18nRepository $repository) {
        $repository->inDB('apostala');

        $repository->set('teste.veio_aqui', 'Veio Aqui', null, 'es');

        $repository->backToDefault();
        return 'ok';
    }
}