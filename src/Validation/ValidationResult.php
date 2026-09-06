<?php

namespace App\Validation;

class ValidationResult
{
    public function __construct(
        private readonly bool $valid,
        private readonly array $errors,
        private readonly array $data
    ) {
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function data(): array
    {
        return $this->data;
    }
}
