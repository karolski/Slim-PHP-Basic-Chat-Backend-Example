<?php

namespace App\Http\Validators;

use Respect\Validation\Exceptions\ValidationException;

interface ValidatorInterface
{
    /**
     * @param array $data
     * @throws ValidationException
     * @return void
     */
    public static function validate(array $data): void;
}