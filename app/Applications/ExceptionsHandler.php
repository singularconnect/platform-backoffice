<?php

namespace App\Applications;

use App\Core\Exceptions\IsInMaintenanceModeException;
use Exception;
use App\Core\Exceptions\Handler as DefExceptionHandler;

use App\Applications\Api\Exceptions\ApiException;
use App\Applications\Standard\Exceptions\StandardException;

use App\Applications\Api\Exceptions\Handler as ApiHandler;
use App\Applications\Standard\Exceptions\Handler as StandardHandler;

class ExceptionsHandler extends DefExceptionHandler
{
    protected $dontReport = [];

    public function render($request, Exception $e) {
        if($e instanceof ApiException
        || starts_with($request->path(), 'api')
        || $request->wantsJson()
        || $request->ajax())
            return (new ApiHandler($this->log))->render($request, $e);

        if($e instanceof StandardException
            || $request->acceptsHtml())
            return (new StandardHandler($this->log))->render($request, $e);

        return parent::render($request, $e);
    }
}
