<?php
namespace App\Applications\Api\Http\Controllers;

use App\Domains\Repositories\ExchangesRepository;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ExchangesController extends BaseController
{
    public function index(ExchangesRepository $repository, Request $request) {
        return parent::indexGeneral($repository, $request);
    }

    public function show(ExchangesRepository $repository, $ids) {
        if( !is_array($ids) ) {
            $aux = [];
            $ids = array_unique(explode(',', $ids));

            foreach($ids as $id)
                $aux[] = explode(':', $id);

            $ids = $aux;
        }

        if( !$res = $repository->find($ids) )
            throw new ResourceNotFoundException;

        if( $res->count() <= 1 )
            return $this->respondWithItem($res[0], $repository->getNewTransformer());

        return $this->respondWithCollection($res, $repository->getNewTransformer());
    }
}