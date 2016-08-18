<?php

namespace App\Applications\Api\Http\Controllers;

use App\Applications\Api\Exceptions\ApiException;
use App\Applications\Api\Exceptions\ApiValidationException;
use App\Domains\Transformers\CurrencyTransfomer;
use App\Domains\Exceptions\DuplicateKeyException;
use App\Domains\Repositories\CurrenciesRepository;
use Illuminate\Http\Request;
use r;

class CurrenciesController extends BaseController
{
    public function index(CurrenciesRepository $repository, Request $request) {
        return parent::indexGeneral($repository, $request);
    }

    public function store(CurrenciesRepository $repository, Request $request) {

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = $repository->validator($request->input());

        if( $validator->fails() )
            throw new ApiValidationException($validator->errors());
        else {
            $inputs = $validator->getData();
            if( !isset($inputs['id']) )
                $inputs['id'] = $inputs['code'];

            if( isset($inputs['code']) )
                unset( $inputs['code'] );

            try {
                $created = $repository->create($inputs);
                return $this->setStatusCode(201)->respondWithItem($created, $repository->getNewTransformer());
            }catch (DuplicateKeyException $ex) {
                throw new ApiException($ex->getMessage(), $ex->getCode(), $ex);
            }
        }
    }

    public function show(CurrenciesRepository $repository, $ids) {
        return parent::showGeneral($repository, $ids);
    }

    public function getDefault(CurrenciesRepository $repository) {
        $res = $repository->find(true, ['index' => 'default'])->first();

        return $this->respondWithItem($res, new CurrencyTransfomer);
    }


}