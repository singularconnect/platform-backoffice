<?php

namespace App\Domains\Exceptions;

use Exception;

class DuplicateKeyException extends DomainsException {

    public function __construct($message = 'Resource already exist', $code = 4001, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}