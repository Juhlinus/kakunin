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
    protected string $validate_key;

    public function __construct()
    {
        $this->validate_key = config(
            'services.kakunin.validation_key', 
            'validate'
        );
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     *
     * @throws \Kakunin\Exceptions\ValidatedException
     */
    protected function passedValidation(): void
    {
        if ($this->request->get($this->validate_key, false)) {
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