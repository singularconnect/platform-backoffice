<?php

namespace App\Applications\Api\Exceptions;


class SearchNotApplicableException extends ApiException {


    public function __construct($message = 'Not have search for this resource.', $code = 1002, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
