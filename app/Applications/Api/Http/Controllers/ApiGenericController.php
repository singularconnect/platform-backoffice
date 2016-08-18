<?php

namespace App\Applications\Api\Http\Controllers;

use App\Core\Http\Controllers\CoreController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

abstract class ApiGenericController extends CoreController
{
    protected $statusCode = 200;

    public function __construct(Manager $fractal, Request $request) {
        $this->fractal = $fractal;

        if ( $request->query->has('include') )
            $this->fractal->parseIncludes($request->query->get('include'));
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function respondNotModified() {
        return response('', 304);
    }

    protected function respondWithItem($item, $callback, $metadata = null) {
        $resource = new Item($item, $callback);

        if( $metadata )
            $resource->setMeta($metadata);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    protected function respondWithCollection($collection, $callback,  $metadata = null) {
        $resource = new Collection($collection, $callback);

        if( $metadata )
            $resource->setMeta($metadata);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    protected function respondWithArray(array $array, array $headers = []) {
        $response = response()->json($array, $this->statusCode, $headers);
        return $response;
    }

    protected function respondWithPaginator($paginator, $callback, $metadata = null) {
//        return $paginator;
        $resource = new Collection($paginator->items(), $callback);

        if( $metadata )
            $resource->setMeta($metadata);

        if( request()->query->has('include') )
            $paginator->addQuery('include', request()->query->get('include'));

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    protected function respondIfDeleted($res)
    {
        return $res ? response('', 204) : response('', 304);
    }
}