<?php

namespace App\Validators;

use App\Exceptions\ValidationException;

/**
 * TODO: gather errors and put them in bag instead of just throwing them
 */
abstract class AbstractValidator
{
    protected array $validated = [];

    private function __construct(protected readonly array $data) {}

    public static function init(array $data): static {
        return new static($data);
    }

    /**
     * @return void
     * @throws ValidationException
     */
    public abstract function validate(): void;

    public function getValidated(): array {
        return $this->validated;
    }
}