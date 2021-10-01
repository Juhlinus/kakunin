<?php

namespace Kakunin;

use Illuminate\Validation\Validator;

class ValidationParser
{
    private Validator $validator;
    private string $validate_key;
    private array $validated_keys = [];

    /**
     * Constructs a new instance.
     *
     * @param      \Illuminate\Validation\Validator  $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
        $this->validate_key = config(
            'services.kakunin.validation_key', 
            'validate'
        );
    }

    /**
     * Returns whether or not we should validate the request
     *
     * @return     boolean
     */
    public function shouldNotValidate(): bool
    {
        $this->validated_keys = array_keys(
            array_filter(
                $this->validator->getData()
            )
        );
        
        if (in_array($this->validate_key, $this->validated_keys)) {
            return false;
        }

        return true;
    }

    /**
     * Get validation key
     *
     * @return     string
     */
    public function getValidationKey(): string
    {
        return $this->validate_key;
    }

    /**
     * Filters out the validation messages we don't need
     *
     * @return     array  
     */
    public function parseValidationMessages(): array
    {
        $parentValidatorMessages = $this->validator->messages()->messages();

        $messages = array_filter($parentValidatorMessages, function ($messageKey) {
            return in_array($messageKey, $this->validated_keys);
        }, ARRAY_FILTER_USE_KEY);

        return $messages;
    }
}