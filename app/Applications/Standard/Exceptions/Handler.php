<?php

namespace App\Applications\Standard\Exceptions;

use Exception;
use ErrorException;
use LogicException;
use App\Core\Exceptions\Handler as DefExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends DefExceptionHandler
{
    protected $dontReport = [];

    public function render($request, Exception $e)
    {
        $message = 'Unhandled Exception';
        $submessage = $e->getMessage();
        $trace = $e->getTrace();
        $code = 501;

        if( $e instanceof HttpException) {
            $http_msgs = [
                '401' => 'Unauthenticated',
                '403' => 'Unauthorized',
                '404' => 'Not Found',
                '500' => 'Server is complicating',
                '501' => 'Server knows not handle it',
                '503' => 'Be right back',
            ];

            $code = $e->getStatusCode();
            $message = $e->getMessage() ?: isset($http_msgs[$code]) ? $http_msgs[$code] : 'Unhandled Exception';
        }
        else if( !($e instanceof StandardException) )
            return parent::render($request, $e);

        return response()->view('standard::errors.' . $code, compact('message', 'code', 'submessage', 'trace'))
            ->setStatusCode($code);
    }
}
