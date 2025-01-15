<?php

namespace App\UseCase\SignUpUseCase;

use App\Mapper\FormData\SignUpFormData;

readonly class SignUpRequest
{
    public function __construct(
        private SignUpFormData $formData,
    ) {}

    public function getFormData(): SignUpFormData
    {
        return $this->formData;
    }
}