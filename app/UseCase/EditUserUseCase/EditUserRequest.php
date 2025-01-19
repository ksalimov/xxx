<?php

namespace App\UseCase\EditUserUseCase;

readonly class EditUserRequest
{
    public function __construct(
        private int $userId
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }
}