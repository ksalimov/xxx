<?php

namespace App\UseCase\UpdateUserUseCase;

use App\Entity\User;
use App\Mapper\FormData\UserFormData;

readonly class UpdateUserRequest
{
    public function __construct(
        private UserFormData $formData,
        private User $user
    ) {}

    public function getFormData(): UserFormData
    {
        return $this->formData;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}