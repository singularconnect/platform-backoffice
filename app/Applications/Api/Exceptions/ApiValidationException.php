<?php

namespace App\Applications\Api\Exceptions;

use Exception;

class ApiValidationException extends ApiException {
    protected $errors = null;

    public function __construct($errors, $message = 'Validation Failed', $code = 1001, Exception $previous = null) {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return null|string
     */
    public function getErrors() {
        return $this->errors;
    }
}
