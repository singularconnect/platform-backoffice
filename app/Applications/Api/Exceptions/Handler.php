<?php

namespace App\Applications\Api\Exceptions;

use App\Domains\Exceptions\DuplicateKeyException;
use Exception;
use App\Core\Exceptions\Handler as DefExceptionHandler;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Handler extends DefExceptionHandler
{
    protected $dontReport = [];

    public function render($request, Exception $e)
    {
        $resp = [
            'code' => $e->getCode() ?: 501,
            'description' => $e->getMessage() ?: 'Unhandled Exception',
        ];
        $stat = 501;

        //usado para encapsulamentos de outros exceptions
        //que não sejam originado da api
        if( $e->getPrevious() ) {
            $e = $e->getPrevious();

            if( $e instanceof DuplicateKeyException)
                $stat = 409;

            $resp = [
                'code' => $e->getCode(),
                'description' => $e->getMessage()
            ];
        }
        else if( $e instanceof ApiValidationException ) {
            $resp = [
                'code' => $e->getCode(),
                'description' => $e->getMessage(),
                'errors' => $e->getErrors()
            ];
            $stat = 422;
        }
        else if( $e instanceof ResourceNotFoundException ) {
            $resp = [
                'code' => $e->getCode() ?: 404,
                'description' => $e->getMessage() ?: 'Resource Not Found'
            ];
            $stat = 404;
        }
        else if( $e instanceof HttpException ) {
            $defMsgs = [
                '401' => 'Unauthenticated',
                '403' => 'Unauthorized',
                '404' => 'Not Found',
                '500' => 'Server is complicating',
                '501' => 'Server knows not handle it',
                '503' => 'Be right back',
            ];
            $resp = [
                'code' => $e->getCode() ?: $e->getStatusCode(),
                'description' => $e->getMessage() ?: isset($defMsgs[$e->getStatusCode()]) ? $defMsgs[$e->getStatusCode()] : 'Unhandled Exception'
            ];
            $stat = $e->getStatusCode();
        }
        else if( !($e instanceof ApiException) && config('app.debug') )
            return parent::render($request, $e);

        return response($resp)->setStatusCode($stat);
    }
}

