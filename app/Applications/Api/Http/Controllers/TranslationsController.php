<?php

namespace App\Applications\Api\Http\Controllers;

use App\Applications\Api\Exceptions\ApiException;
use App\Applications\Api\Exceptions\ApiValidationException;
use App\Domains\Repositories\I18nRepository;
use App\Domains\Repositories\TranslationsRepository;
use Illuminate\Http\Request;

class TranslationsController extends BaseController
{
    public function show(TranslationsRepository $repository, $id) {
        $res = $repository->getByLanguage($id);
        return $this->respondWithCollection($res, $repository->getNewTransformer());
    }

    public function store(I18nRepository $repository, Request $request) {
        $inputs = $request->input();

        if( !isset($inputs['translation']) || !is_array($inputs['translation']) )
            throw new ApiException('Wrong format data');

        $iptrans = $inputs['translation'];

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = $repository->translationRepository()->validator($iptrans);

        if( $validator->fails() )
            throw new ApiValidationException($validator->errors());

        $key = $iptrans['key'];
        $target = $iptrans['target'];
        $context = isset($iptrans['context']) ? $iptrans['context'] : 'default';
        $language = isset($iptrans['language']) ? $iptrans['language'] : 'en';

        $created = $repository->set($key, $target, $context, $language);

        return $this->setStatusCode(201)->respondWithItem($created, $repository->translationRepository()->getNewTransformer());
    }

    public function delete(I18nRepository $repository, $id, $key) {
        return $this->respondIfDeleted($repository->del($key, null, $id));
    }
}