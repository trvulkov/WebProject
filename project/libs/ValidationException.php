<?php

class ValidationException extends Exception {
    private array $errors;

    public function __construct(array $errors) {
        $this->errors = $errors;
        parent::__construct("Invalid!");
    }

    public function getErrors(): array {
        return $this->errors;
    }
}

?>