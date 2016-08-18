<?php

namespace App\Applications\Api\Http\Controllers;

use App\Applications\Api\Exceptions\ApiException;
use App\Applications\Api\Exceptions\ApiValidationException;
use App\Domains\Repositories\I18nRepository;
use App\Domains\Repositories\TranslationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TranslationsController extends BaseController
{
    public function show(TranslationsRepository $repository, $id) {
        $res = $repository->getByLanguage($id);
        return $this->respondWithCollection($res, $repository->getNewTransformer());
    }

    public function showfile(I18nRepository $repository, $id) {
        return $repository->getLikeI18nFile($id);
    }

    public function store(I18nRepository $repository, Request $request) {
        $iptrans = Arr::get($request->input(), 'translation');

        if( empty($iptrans) || !is_array($iptrans) )
            throw new ApiException('Wrong format data');

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = $repository->translationRepository()->validator($iptrans);

        if( $validator->fails() )
            throw new ApiValidationException($validator->errors());

        $key = $iptrans['key'];
        $target = $iptrans['target'];
        $context = Arr::get($iptrans, 'context');
        $language = Arr::get($iptrans, 'language');

        $created = $repository->set($key, $target, $context, $language);

        return $this->setStatusCode(201)->respondWithItem($created, $repository->translationRepository()->getNewTransformer());
    }

    public function delete(I18nRepository $repository, $id, $key) {
        return $this->respondIfDeleted($repository->del($key, null, $id));
    }
}