<?php

namespace App\Applications\Api\Http\Controllers;

use App\Applications\Api\Exceptions\ApiValidationException;
use App\Applications\Api\Exceptions\SearchNotApplicableException;
use App\Domains\Transformers\CurrencyTransfomer;
use App\Domains\Repositories\CommonRepository;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Validator;

abstract class BaseController extends ApiGenericController
{
    public function indexGeneral(CommonRepository $repository, Request $request) {
        //defaults
        $q = '';
        $fields = $repository->getFieldSearchDefault();
        $take = $repository->getPerPageDefault();
        $orderby = $repository->getOrderByDefault();;
        $direction = 'asc';
        $reqall = 0;

        /** @var $validator \Illuminate\Contracts\Validation\Validator */
        $validator = Validator::make($request->query->all(), $repository->toValidatePagination());

        if( $validator->fails() ) {
            if( array_key_exists('q', $validator->failed())
            or array_key_exists('take', $validator->failed()) )
                throw new ApiValidationException($validator->errors());

            extract(array_diff_key($request->query->all(), $validator->failed()));
        }else
            extract($request->query->all());

        if( !empty($q) ) {
            if( is_null($fields) )
                throw new SearchNotApplicableException;

            $fields = array_intersect(explode(',', $fields), $repository->searchFields());
            $result = $repository->searchPaginate($q, $fields, $reqall, $take, $orderby, $direction);
        } else
            $result = $repository->paginate($take, $orderby, $direction);

        return $this->respondWithPaginator($result, $repository->getNewTransformer());
    }

    public function showGeneral(CommonRepository $commonRepository, $ids) {
        if( !is_array($ids) )
            $ids = array_unique(explode(',', $ids));

        if( !$res = $commonRepository->find($ids))
            throw new ResourceNotFoundException;

        if( $res->count() <= 1 )
            return $this->respondWithItem($res[0], $commonRepository->getNewTransformer());

        return $this->respondWithCollection($res, $commonRepository->getNewTransformer());
    }
}