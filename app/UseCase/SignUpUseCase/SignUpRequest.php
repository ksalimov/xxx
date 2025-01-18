<?php

namespace App\UseCase\SignUpUseCase;

use App\Mapper\FormData\UserFormData;

readonly class SignUpRequest
{
    public function __construct(
        private UserFormData $formData,
    ) {}

    public function getFormData(): UserFormData
    {
        return $this->formData;
    }
}