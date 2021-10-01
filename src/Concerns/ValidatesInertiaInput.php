<?php

namespace Kakunin\Concerns;

use Kakunin\ValidationParser;
use Kakunin\Exceptions\ValidatedException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

/**
 * Provides instant form validation behaviour
 */
trait ValidatesInertiaInput
{
    /**
     * Handle a passed validation attempt.
     *
     * @return void
     *
     * @throws \Kakunin\Exceptions\ValidatedException
     */
    protected function passedValidation(): void
    {
        $validationParser = new ValidationParser($this->getValidatorInstance());

        if ($this->request->get($validationParser->getValidationKey(), false)) {
            throw new ValidatedException();
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        $validationParser = new ValidationParser($validator);

        if ($validationParser->shouldNotValidate()) {
            parent::failedValidation($validator);
        }

        $messages = $validationParser->parseValidationMessages();

        $exception = new ValidationException($validator);

        throw $exception->withMessages($messages);
    }
}