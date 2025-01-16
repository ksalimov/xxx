<?php

namespace App\UseCase\VerifyEmailUseCase;

readonly class VerifyEmailRequest
{
    public function __construct(
        private string $token
    ) {}

    public function getToken(): string
    {
        return $this->token;
    }
}