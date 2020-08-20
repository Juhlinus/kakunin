<?php

namespace Kakunin\Concerns;

use Kakunin\Exceptions\ValidatedException;

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
}