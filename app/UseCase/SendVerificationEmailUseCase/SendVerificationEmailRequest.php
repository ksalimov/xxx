<?php

namespace App\UseCase\SendVerificationEmailUseCase;

readonly class SendVerificationEmailRequest
{
    public function __construct(
        private string $userEmail
    ) {}

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }
}