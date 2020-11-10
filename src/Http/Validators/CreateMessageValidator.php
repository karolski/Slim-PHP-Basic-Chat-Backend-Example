<?php

namespace App\Http\Validators;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class CreateMessageValidator implements ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public static function validate(array $data): void
    {
        v::key('content', v::stringType()->length(0, 10000))
            ->key('to', v::uuid())
            ->assert($data);
    }
}