<?php

namespace Kakunin\Concerns;

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
     */
    protected function passedValidation()
    {
        $validateKey = config(
            'services.kakunin.validation_key', 
            'validate'
        );

        if ($this->request->get($validateKey, false)) {
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
    protected function failedValidation(Validator $validator)
    {
        $validatedKeys = array_keys(
            array_filter(
                $validator->getData()
            )
        );

        $validateKey = config(
            'services.kakunin.validation_key', 
            'validate'
        );

        if (in_array($validateKey, $validatedKeys)) {
            parent::failedValidation($validator);
        }

        $validatorMessages = $validator->getMessageBag()->getMessages();

        $messages = array_filter($validatorMessages, function ($messageKey) use ($validatedKeys) {
            return in_array($messageKey, $validatedKeys);
        }, ARRAY_FILTER_USE_KEY);

        $exception = new ValidationException($validator);

        throw $exception->withMessages($messages);
    }
}