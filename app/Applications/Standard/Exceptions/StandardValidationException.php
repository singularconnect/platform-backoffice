<?php

namespace App\Applications\Standard\Exceptions;

use Exception;

class StandardValidationException extends StandardException {
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
