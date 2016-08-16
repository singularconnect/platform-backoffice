<?php

namespace App\Applications\Api\Http\Controllers;

use App\Domains\Repositories\TranslationsRepository;

class TranslationsController extends BaseController
{
    public function show(TranslationsRepository $repository, $id) {
        $res = $repository->getByLanguage($id);
        return $this->respondWithCollection($res, $repository->getNewTransformer());
    }
}