<?php

namespace App\UseCase\DeleteUserUseCase;

readonly class DeleteUserRequest
{
    public function __construct(
        private int $id,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
}